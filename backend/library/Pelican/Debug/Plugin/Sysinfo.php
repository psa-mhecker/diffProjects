<?php
/**
 */
class Pelican_Debug_Plugin_Sysinfo implements Pelican_Debug_Plugin_Interface
{
    protected $image_path = '/library/Pelican/Debug/public/images';

    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'sysinfo';

    /**
     * Creating time plugin.
     */
    public function __construct()
    {
    }

    /**
     * Gets identifier for this plugin.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Gets menu Pelican_Index_Tab for the Debugbar.
     *
     * @return string
     */
    public function getTab()
    {
        return 'Sysinfo : '.implode(' - ', $this->load);
    }

    public static function getAlert($value)
    {
        if ($value > 3) {
            $value = '<span class="alert">'.$value.'</span>';
        }

        return $value;
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {
        $panel = Pelican_Debug::getFieldset('Hostname', $this->hostname());
        $panel .= Pelican_Debug::getFieldset('Virtual Host', $this->vhostname());
        $panel .= Pelican_Debug::getFieldset('IP', $this->ip_addr());
        $panel .= Pelican_Debug::getFieldset('Uptime', $this->uptime());
        $panel .= Pelican_Debug::getFieldset('Load Average', $this->load_average());
        $panel .= $this->memory();
        //$panel .= Pelican_Debug::getFieldset('Cpu', $this->cpu());
        //$panel .= Pelican_Debug::getFieldset('Filesystem', $this->filesystems());
        return $panel;
    }

    // get our apache SERVER_NAME or vhost
    public function hostname()
    {
        if (! ($result = getenv('HOSTNAME'))) {
            $result = 'N.A.';
        }

        return $result;
    }

    // get our apache SERVER_NAME or vhost
    public function vhostname()
    {
        if (! ($result = getenv('SERVER_NAME'))) {
            $result = 'N.A.';
        }

        return $result;
    }

    // get the IP address of our canonical hostname
    public function ip_addr()
    {
        if (! ($result = getenv('SERVER_ADDR'))) {
            $result = gethostbyname($this->chostname());
        }

        return $result;
    }

    public function uptime()
    {
        $fd = fopen('/proc/uptime', 'r');
        $ar_buf = explode(' ', fgets($fd, 4096));
        fclose($fd);

        $result = trim($ar_buf[0]);

        $d = 60 * 60 * 24;
        $h = 60 * 60;
        $m = 60;
        $jours = floor($result / $d);
        $heures = floor(($result - $jours * $d) / $h);
        $minutes = floor(($result - $jours * $d - $heures * $h) / $m);

        setlocale(LC_ALL, 'fr_FR');
        $date = date('l d F Y H:i:s', time()-$result);

        return $jours.' j. '.$heures.' h. '.$minutes.' m.<br /><br />'.$date;
    }

    public function cpu()
    {
        exec('top -b -n1', $top, $error);
        $this->top = $top;
        if ($this->top) {
            $temp = explode(',', str_replace('Cpu(s):', '', $this->top[2]));
            foreach ($temp as $item) {
                $temp2 = explode(' ', trim($item));
                $tab[$temp2[1]] = $temp2[0];
            }
        }
        $return = Pelican_Debug::getTable($tab);

        return $return;
    }

    public function memory()
    {
        if ($fd = fopen('/proc/meminfo', 'r')) {
            $results['ram'] = array();
            $results['swap'] = array();
            $results['devswap'] = array();

            while ($buf = fgets($fd, 4096)) {
                if (preg_match('/^MemTotal:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
                    $results['ram']['total'] = $ar_buf[1];
                } elseif (preg_match('/^MemFree:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
                    $results['ram']['t_free'] = $ar_buf[1];
                } elseif (preg_match('/^Cached:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
                    $results['ram']['cached'] = $ar_buf[1];
                } elseif (preg_match('/^Buffers:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
                    $results['ram']['buffers'] = $ar_buf[1];
                } elseif (preg_match('/^SwapTotal:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
                    $results['swap']['total'] = $ar_buf[1];
                } elseif (preg_match('/^SwapFree:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
                    $results['swap']['free'] = $ar_buf[1];
                }
            }
            fclose($fd);

            $results['ram']['t_used'] = $results['ram']['total'] - $results['ram']['t_free'];
            $results['ram']['percent'] = round(($results['ram']['t_used'] * 100) / $results['ram']['total']);
            $results['swap']['used'] = $results['swap']['total'] - $results['swap']['free'];
            $results['swap']['percent'] = round(($results['swap']['used'] * 100) / $results['swap']['total']);

            // values for splitting memory usage
            if (isset($results['ram']['cached']) && isset($results['ram']['buffers'])) {
                $results['ram']['app'] = $results['ram']['t_used'] - $results['ram']['cached'] - $results['ram']['buffers'];
                $results['ram']['app_percent'] = round(($results['ram']['app'] * 100) / $results['ram']['total']);
                $results['ram']['buffers_percent'] = round(($results['ram']['buffers'] * 100) / $results['ram']['total']);
                $results['ram']['cached_percent'] = round(($results['ram']['cached'] * 100) / $results['ram']['total']);
            }

            $swaps = file('/proc/swaps');
            for ($i = 1; $i < (sizeof($swaps)); $i ++) {
                $ar_buf = preg_split('/\s+/', $swaps[$i], 6);
                $results['devswap'][$i - 1] = array();
                $results['devswap'][$i - 1]['dev'] = $ar_buf[0];
                $results['devswap'][$i - 1]['total'] = $ar_buf[2];
                $results['devswap'][$i - 1]['used'] = $ar_buf[3];
                $results['devswap'][$i - 1]['free'] = ($results['devswap'][$i - 1]['total'] - $results['devswap'][$i - 1]['used']);
                $results['devswap'][$i - 1]['percent'] = round(($ar_buf[3] * 100) / $ar_buf[2]);
            }
        } else {
            $results['ram'] = array();
            $results['swap'] = array();
            $results['devswap'] = array();
        }

        $tab = array();
        $tab['Total'] = $results['ram']['total'];
        $tab['Used'] = $results['ram']['t_used'];
        $tab['Free'] = $results['ram']['t_free'];
        $tab['Buffers'] = $results['ram']['buffers'];
        $return[] = Pelican_Debug::getFieldset('Ram', Pelican_Debug::getTable($tab));
        $tab = array();
        $tab['Total'] = $results['swap']['total'];
        $tab['Used'] = $results['swap']['used'];
        $tab['Free'] = $results['swap']['free'];
        $tab['Cached'] = $results['ram']['cached'];
        $return[] = Pelican_Debug::getFieldset('Swap', Pelican_Debug::getTable($tab));

        return implode('', $return);
    }

    public function filesystems()
    {
        global $show_bind;
        $fstype = array();
        $fsoptions = array();

        $df = $this->execute_program('df', '-kP');
        $mounts = explode("\n", $df);

        $buffer = $this->execute_program("mount");
        $buffer = explode("\n", $buffer);

        $j = 0;
        foreach ($buffer as $line) {
            preg_match("/(.*) on (.*) type (.*) \((.*)\)/", $line, $result);
            if (count($result) == 5) {
                $dev = $result[1];
                $mpoint = $result[2];
                $type = $result[3];
                $options = $result[4];
                $fstype[$mpoint] = $type;
                $fsdev[$dev] = $type;
                $fsoptions[$mpoint] = $options;

                foreach ($mounts as $line2) {
                    if (preg_match("#^".$result[1]."#", $line2)) {
                        $line2 = preg_replace("#^".$result[1]."#", "", $line2);
                        $ar_buf = preg_split("/(\s+)/", $line2, 6);
                        $ar_buf[0] = $result[1];

                        if ($this->hide_mount($ar_buf[5]) || $ar_buf[0] == "") {
                            continue;
                        }

                        if ($show_bind || ! stristr($fsoptions[$ar_buf[5]], "bind")) {
                            $results[$j] = array();
                            $results[$j]['disk'] = $ar_buf[0];
                            $results[$j]['size'] = $ar_buf[1];
                            $results[$j]['used'] = $ar_buf[2];
                            $results[$j]['free'] = $ar_buf[3];
                            $results[$j]['percent'] = round(($results[$j]['used'] * 100) / $results[$j]['size']).'%';
                            $results[$j]['mount'] = $ar_buf[5];
                            ($fstype[$ar_buf[5]]) ? $results[$j]['fstype'] = $fstype[$ar_buf[5]] : $results[$j]['fstype'] = $fsdev[$ar_buf[0]];
                            $results[$j]['options'] = $fsoptions[$ar_buf[5]];
                            $j ++;
                        }
                    }
                }
            }
        }

        $filesystem[] = '<tr><th>Point</th><th>Type</th><th>Partition</th><th>Utilisation</th><th>Libre</th><th>Occupé</th><th>Taille</th></tr>';

        foreach ($results as $line) {
            $comp = 'bar';
            if ($line['percent'] > 90) {
                $comp = 'redbar';
            }
            $filesystem[] = '<tr><td>'.$line['mount'].'</td><td>'.$line['fstype'].'</td><td>'.$line['disk'].'</td><td>'.

            '<nobr><img height="16" src="'.$this->image_path.'/'.$comp.'_left.gif" alt=""><img height="16" src="'.$this->image_path.'/'.$comp.'_middle.gif" width="'.(150 * $line['percent'] / 100).'" alt=""><img height="16" src="'.$this->image_path.'/'.$comp.'_right.gif" alt="">&nbsp;'.$line['percent'].'</nobr>'.

            '</td><td class="right">'.$this->format_bytesize($line['free']).'</td><td class="right">'.$this->format_bytesize($line['used']).'</td><td class="right">'.$this->format_bytesize($line['size']).'</td></tr>';
        }

        return '<table>'.implode('', $filesystem).'</table>';
    }

    public function load_average()
    {
        $this->load = sys_getloadavg();
        $this->load[0] = self::getAlert($this->load[0]);
        $this->load[1] = self::getAlert($this->load[1]);
        $this->load[2] = self::getAlert($this->load[2]);

        $tab['1 minute'] = $this->load[0];
        $tab['5 minutes'] = $this->load[1];
        $tab['16 minutes'] = $this->load[2];
        $return = Pelican_Debug::getTable($tab);

        return $return;
    }

    public function execute_program($program, $args = '')
    {
        $buffer = '';
        $program = $this->find_program($program);

        if (! $program) {
            return;
        }
        // see if we've gotten a |, if we have we need to do patch checking on the cmd
        if ($args) {
            $args_list = explode(' ', $args);
            for ($i = 0; $i < count($args_list); $i ++) {
                if ($args_list[$i] == '|') {
                    $cmd = $args_list[$i + 1];
                    $new_cmd = $this->find_program($cmd);
                    $args = preg_replace("#\| $cmd#", "| $new_cmd", $args);
                }
            }
        }
        // we've finally got a good cmd line.. execute it
        if ($fp = popen("$program $args", 'r')) {
            while (! feof($fp)) {
                $buffer .= fgets($fp, 4096);
            }

            return trim($buffer);
        }
    }

    // Find a system program.  Do path checking
    public function find_program($program)
    {
        $path = array('/bin' , '/sbin' , '/usr/bin' , '/usr/sbin' , '/usr/local/bin' , '/usr/local/sbin');

        if (function_exists("is_executable")) {
            while ($this_path = current($path)) {
                if (is_executable("$this_path/$program")) {
                    return "$this_path/$program";
                }
                next($path);
            }
        } else {
            return strpos($program, '.exe');
        }

        return;
    }

    // A helper function, when passed a number representing KB,
    // and optionally the number of decimal places required,
    // it returns a formated number string, with unit identifier.
    public function format_bytesize($kbytes, $dec_places = 2)
    {
        $spacer = '&nbsp;';
        if ($kbytes > 1048576) {
            $result = sprintf('%.'.$dec_places.'f', $kbytes / 1048576);
            $result .= $spacer.'Go';
        } elseif ($kbytes > 1024) {
            $result = sprintf('%.'.$dec_places.'f', $kbytes / 1024);
            $result .= $spacer.'Mo';
        } else {
            $result = sprintf('%.'.$dec_places.'f', $kbytes);
            $result .= $spacer.'Ko';
        }

        return $result;
    }

    // Check if a string exist in the global $hide_mounts.
    // Return true if this is the case.
    public function hide_mount($mount)
    {
        global $hide_mounts;
        if (isset($hide_mounts) && is_array($hide_mounts) && in_array($mount, $hide_mounts)) {
            return true;
        } else {
            return false;
        }
    }
}
