<?php
namespace Itkg\Tests\Writer;


use Itkg\Writer\PelicanWriter;
use \Phake;

class PelicanWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PelicanWriter
     */
    private $writer;

    /**
     * @var \Pelican_Db
     */
    private $con;

    /**
     * @var \XtoY\Reporter\ReporterInterface
     */
    private $reporter;

    public function setUp()
    {
        $this->con = Phake::Mock('\Pelican_Db');
        $this->reporter = Phake::Mock('XtoY\Reporter\ReporterInterface');
        $options= ['table'=>'table'];
        $this->writer = new PelicanWriter($options);
        $this->writer->setDDN($this->con);
        $this->writer->open();
        $this->writer->preprocessing();
    }

    public function testWrite()
    {
        $line =['dummy'=>0];

        $this->writer->write($line);
        Phake::verify($this->con,Phake::times(1))->insertQuery(Phake::anyParameters());

        $this->writer->setReporter($this->reporter);
        $this->writer->write($line);
        Phake::verify($this->con,Phake::times(2))->insertQuery(Phake::anyParameters());
        Phake::verify($this->reporter,Phake::times(1))->setWrittenLines(Phake::anyParameters());
    }

    public function testWriteAll()
    {
        $table = array();
        $table[] = ['dummy'=>0];
        $table[] = ['dummy'=>1];
        $table[] = ['dummy'=>2];

        $this->writer->writeAll($table);
        Phake::verify($this->con,Phake::times(3))->insertQuery(Phake::anyParameters());


    }

    public function testRollback()
    {

        $this->writer->rollback();
        Phake::verify($this->con,Phake::times(1))->rollback();
    }

    public function testopen()
    {
        Phake::verify($this->con,Phake::times(0))->beginTrans();

        $options= ['table'=>'table','transaction'=>true];
        $writer = new PelicanWriter($options);
        $writer->setDDN($this->con);
        $writer->open();
        Phake::verify($this->con,Phake::times(1))->beginTrans(Phake::anyParameters());

    }


    public function testClose()
    {

        $this->writer->close();
        Phake::verify($this->con,Phake::times(0))->commit();

        $options= ['table'=>'table','transaction'=>true];
        $writer = new PelicanWriter($options);
        $writer->setDDN($this->con);
        $writer->open();
        $writer->preprocessing();
        $writer->close();
        Phake::verify($this->con,Phake::times(1))->commit();

    }
}


