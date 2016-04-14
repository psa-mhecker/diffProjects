<?php

class Pelican_Session_FileSystem implements Zend_Session_SaveHandler_Interface
{
    private $level;

    private $savePath;

    private $filename;

    /*
     * @see Zend_Session_SaveHandler_Interface::open()
     */
    public function open($savePath, $sessionName)
    {
        $path = explode(';', $savePath);
        if (count($path) == 1) {
            $this->level = 0;
            $this->savePath = $path[0];
        } else {
            $last = count($path) - 1;
            $this->level = (int) $path[0];
            $this->savePath = $path[$last];
        }

        return true;
    }

    /*
     * @see Zend_Session_SaveHandler_Interface::close()
     */
    public function close()
    {
        return true;
    }

    /*
     * @see Zend_Session_SaveHandler_Interface::read()
     */
    public function read($id)
    {
        // on désactive : on suppose que les répertoires existent => gain de temps via NFS
        // $this->_verifyDir($id);

        // crée le fichier s'il n'existe pas et repousse sa date d'accès
        touch($this->savePath.'/'.$this->_getFileName($id));

        $return = (string) file_get_contents($this->savePath.'/'.$this->_getFileName($id));

        // sauvegarde de la valeur initiale pour contrôle d'écriture
        $this->initialValue = $return;

        return $return;
    }

    /*
     * @see Zend_Session_SaveHandler_Interface::write()
     */
    public function write($id, $data)
    {
        // si lma valeur de lma session a évolué, on l'enregistre
        if ($this->initialValue != $data) {
            $return = file_put_contents($this->savePath.'/'.$this->_getFileName($id), $data) === false ? false : true;
        }

        return $return;
    }

    /*
     * @see Zend_Session_SaveHandler_Interface::destroy()
     */
    public function destroy($id)
    {
        $file = $this->savePath.'/'.$this->_getFileName($id);
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    /*
     * @see Zend_Session_SaveHandler_Interface::gc()
     */
    public function gc($maxlifetime)
    {
        // sur NFS, la purge est faite par batch
        return true;
    }

    /**
     * @param string $id
     *                   Session id
     */
    protected function _verifyDir($id)
    {
        $dir = dirname($this->savePath.'/'.$this->_getFileName($id));

        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * @param string $id
     *                   Session id
     *
     * @return string
     */
    protected function _getFileName($id)
    {
        if (empty($this->filename)) {
            $sub = array();
            if ($this->level) {
                for ($i = 0; $i < $this->level; $i ++) {
                    $sub[] = $id{$i};
                }
            }

            $this->filename = trim(implode('/', $sub).'/fsess_'.$id, '/');
        }

        return $this->filename;
    }
}
