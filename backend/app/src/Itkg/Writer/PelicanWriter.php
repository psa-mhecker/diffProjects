<?php

namespace Itkg\Writer;

use XtoY\Options\Optionnable;
use XtoY\Reporter\ReporterInterface;
use XtoY\Writer\WriterInterface;

class PelicanWriter extends Optionnable implements WriterInterface
{
    /** @var  \Pelican_Db */
    protected $con;

    /**
     * @var array
     */
    protected $oldData;

    /**
     * @var \ReflectionClass
     */
    protected $reflexion;

    /**
     * @var ReporterInterface
     */
    protected $reporter;

    /**
     * @var int
     */
    protected $line;

    public function __construct($options)
    {
        parent::__construct();

        $this->addRequiredOption('table');
        $this->addOption('transaction', false);
        $this->getOptionManager()->init($options);
    }

    /**
     * @param $ddn
     * @codeCoverageIgnore
     *
     * @return $this
     */
    public function setDDN($ddn)
    {
        $this->con = $ddn;

        return $this;
    }

    /**
     * @param ReporterInterface $reporter
     * @codeCoverageIgnore
     *
     * @return $this
     */
    public function setReporter(ReporterInterface $reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * @param array $line
     *
     * @return $this;
     */
    public function write($line)
    {
        $this->reflexion->setStaticPropertyValue('values', $line);
        $options = $this->getOptions();
        $this->con->insertQuery($options['table']);
        if ($this->reporter instanceof ReporterInterface) {
            $this->reporter->setWrittenLines(++$this->line);
        }

        return $this;
    }

    /**
     * @param array $table
     *
     * @return $this
     */
    public function writeAll($table)
    {
        foreach ($table as $line) {
            $this->write($line);
        }

        return $this;
    }

    /**
     * @return $this
     * @codeCoverageIgnore
     */
    public function postprocessing()
    {
        $this->reflexion->setStaticPropertyValue('values', $this->oldData);

        return $this;
    }

    /**
     * @return $this
     * @codeCoverageIgnore
     */
    public function preprocessing()
    {
        $this->oldData = $this->reflexion->getStaticPropertyValue('values');

        return $this;
    }

    /**
     * @return $this
     */
    public function open()
    {
        $this->line = 0;
        $options = $this->getOptions();
        if ($options['transaction']) {
            $this->con->beginTrans();
        }
        $this->reflexion = new \ReflectionClass($this->con);

        return $this;
    }

    /**
     * @return $this
     */
    public function close()
    {
        unset($this->reflexion);
        $options = $this->getOptions();
        if ($options['transaction']) {
            $this->con->commit();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function rollback()
    {
        $this->con->rollback();
        $this->reflexion->setStaticPropertyValue('values', $this->oldData);

        return $this;
    }
}

