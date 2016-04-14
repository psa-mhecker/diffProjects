<?php
/**
 * Classe de cryptage.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Classe de cryptage.
 *
 * @author ???
 *
 * @since 06/03/2006
 *
 * @version 1.0
 */
class Pelican_Security_Crypt
{
    /**
     * __DESC__.
     *
     * @access private
     *
     * @var __TYPE__
     */
    public $_algorithm;

    /**
     * __DESC__.
     *
     * @access private
     *
     * @var __TYPE__
     */
    public $_mode;

    /**
     * __DESC__.
     *
     * @access private
     *
     * @var __TYPE__
     */
    public $_random_source;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $cleartext;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $ciphertext;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $iv;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $algorithm     (option) __DESC__
     * @param string $mode          (option) __DESC__
     * @param string $random_source (option) __DESC__
     *
     * @return Pelican_Security_Crypt
     */
    public function Pelican_Security_Crypt($algorithm = MCRYPT_BLOWFISH, $mode = MCRYPT_MODE_CBC, $random_source = MCRYPT_DEV_URANDOM)
    {
        $this->_algorithm = $algorithm;
        $this->_mode = $mode;
        $this->_random_source = $random_source;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function generate_iv()
    {
        $this->iv = mcrypt_create_iv(mcrypt_get_iv_size($this->_algorithm, $this->_mode), $this->_random_source);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function encrypt()
    {
        $this->ciphertext = mcrypt_encrypt($this->_algorithm, $_SERVER['CRYPT_KEY'], $this->cleartext, $this->_mode, $this->iv);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function decrypt()
    {
        $this->cleartext = mcrypt_decrypt($this->_algorithm, $_SERVER['CRYPT_KEY'], $this->ciphertext, $this->_mode, $this->iv);
    }

    /**
     * Crypte avec l'algorithme Triple DES.
     *
     * @access public
     *
     * @param string $sVar (option) __DESC__
     *
     * @return string
     */
    public function encrypt3DES($sVar = '')
    {
        $sResult = '';
        $j = strlen($sVar) % 8;
        if ($j != 0) {
            for ($i = 0;$i < 8 - $j;$i++) {
                $sVar .= " ";
            }
        }
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, Pelican::$config['SECURITY']['CRYPT_KEY'], $iv);
        $sResult = mcrypt_generic($td, $sVar);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $sResult = strtoupper(bin2hex($sResult));

        return $sResult;
    }

    /**
     * Décrypte une chaîne cryptée avec l'algorithme Triple DES.
     *
     * @access public
     *
     * @param string $sVar (option) __DESC__
     *
     * @return string
     */
    public function decrypt3DES($sVar = '')
    {
        $sResult = '';
        $sString = Pelican_Security_Crypt::hex2bin($sVar);
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, Pelican::$config['SECURITY']['CRYPT_KEY'], $iv);
        $sResult = mdecrypt_generic($td, $sString);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        unset($sString);

        return $sResult;
    }

    /**
     * Encrypte avec l'algorithme AES 128.
     *
     * @access public
     *
     * @param string $sVar (option) __DESC__
     *
     * @return string
     */
    public function encryptAES128($sVar = '')
    {
        $text = $sVar;
        $secret_key = Pelican::$config['SECURITY']['CRYPT_KEY'];
        /* Cr�ation du vecteur IV */
        $size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
        //echo ('iv=' . bin2hex($iv));
        /* Gestion du padding PKCS#7 */
        $packing = ord($text{strlen($text) - 1});
        if ($packing and ($packing < $size)) {
            for ($P = strlen($text) - 1;$P >= strlen($text) - $packing;$P--) {
                if (ord($text{$P}) != $packing) {
                    $packing = 0;
                }
            }
        }
        $len = strlen($text);
        $padding = $size - ($len % $size);
        $text .= str_repeat(chr($padding), $padding);
        /* Initialisation de l'dncryption handle */
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, "", MCRYPT_MODE_ECB, $iv);
        mcrypt_generic_init($td, $secret_key, $iv);
        $cyper_text = mcrypt_generic($td, $text);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($cyper_text);
    }

    /**
     * Decrypte avec l'algorithme AES 128.
     *
     * @access public
     *
     * @param string $sVar (option) __DESC__
     *
     * @return string
     */
    public function decryptAES128($sVar = '')
    {
        $encrypted_text = $this->hex2bin($sVar);
        $secret_key = Pelican::$config['SECURITY']['CRYPT_KEY'];
        /* Cr�ation du vecteur IV */
        $size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
        /* Initialisation de l'decryption handle */
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, "", MCRYPT_MODE_ECB, $iv);
        mcrypt_generic_init($td, $secret_key, $iv);
        $out_text = mdecrypt_generic($td, $encrypted_text);
        /* Fermeture */
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        /* Gestion du unpadding PKCS#7 */
        $len = strlen($out_text);
        $padding = $size - ($len % $size);
        $out_text = substr($out_text, 0, -1 * $padding);

        return $out_text;
    }

    /**
     * Fonction de conversion hexadecimal->binaire.
     *
     * @access public
     *
     * @param string $hexdata Chaîne hexadécimale
     *
     * @return string
     */
    public function hex2bin($hexdata)
    {
        for ($i = 0;$i < strlen($hexdata);$i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $encryption (option) __DESC__
     * @param string   $seed       (option) __DESC__
     * @param string   $plaintext  (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getSalt($encryption = 'md5-hex', $seed = '', $plaintext = '')
    {
        // Encrypt the password.
        switch ($encryption) {
            case 'crypt':
            case 'crypt-des':
                if ($seed) {
                    return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 2);
                } else {
                    return substr(md5(mt_rand()), 0, 2);
                }
            break;
            case 'crypt-md5':
                if ($seed) {
                    return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 12);
                } else {
                    return '$1$'.substr(md5(mt_rand()), 0, 8).'$';
                }
            break;
            case 'crypt-blowfish':
                if ($seed) {
                    return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 16);
                } else {
                    return '$2$'.substr(md5(mt_rand()), 0, 12).'$';
                }
            break;
            case 'ssha':
                if ($seed) {
                    return substr(preg_replace('|^{SSHA}|', '', $seed), -20);
                } else {
                    return mhash_keygen_s2k(MHASH_SHA1, $plaintext, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
                }
            break;
            case 'smd5':
                if ($seed) {
                    return substr(preg_replace('|^{SMD5}|', '', $seed), -16);
                } else {
                    return mhash_keygen_s2k(MHASH_MD5, $plaintext, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
                }
            break;
            case 'aprmd5':
                /* 64 characters that are valid for APRMD5 passwords. */
                $APRMD5 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                if ($seed) {
                    return substr(preg_replace('/^\$apr1\$(.{8}).*/', '\\1', $seed), 0, 8);
                } else {
                    $salt = '';
                    for ($i = 0;$i < 8;$i++) {
                        $salt .= $APRMD5{rand(0, 63) };
                    }

                    return $salt;
                }
            break;
            default:
                $salt = '';
                if ($seed) {
                    $salt = $seed;
                }

                return $salt;
            break;
        }
    }
}
