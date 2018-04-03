<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * files
 *
 * @ORM\Table(name="files")
 * @ORM\Entity
 */
class Files
{
    /**
     * @var string
     *
     * @ORM\Column(name="f_name", type="string", length=255, nullable=true)
     */
    private $fname;

    /**
     * @ORM\Column(type="integer")
     */
    private $offerte_id;

    /**
     * @var string
     *
     * @ORM\Column(name="f_url", type="string", length=255, nullable=true)
     */
    private $furl;

    /**
     * @var string
     *
     * @ORM\Column(name="f_extension", type="string", length=255, nullable=true)
     */
    private $fextension;

    /**
     * @var string
     *
     * @ORM\Column(name="f_size", type="string", length=255, nullable=true)
     */
    private $fsize;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    private $file;
    /**
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }
    /**
     * @param string $fname
     */
    public function setFname($fname)
    {
        $this->fname = $fname;
    }
    /**
     * @return string
     */
    public function getFurl()
    {
        return $this->furl;
    }
    /**
     * @param string $furl
     */
    public function setFurl($furl)
    {
        $this->furl = $furl;
    }
    /**
     * @return string
     */
    public function getFsize()
    {
        return $this->fsize;
    }
    /**
     * @param string $fsize
     */
    public function setFsize($fsize)
    {
        $this->fsize = $fsize;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getOfferteId()
    {
        return $this->offerte_id;
    }

    /**
     * @param mixed $offerte_id
     */
    public function setOfferteId($offerte_id)
    {
        $this->offerte_id = $offerte_id;
    }

    /**
     * @return string
     */
    public function getFextension()
    {
        return $this->fextension;
    }

    /**
     * @param string $fextension
     */
    public function setFextension($fextension)
    {
        $this->fextension = $fextension;
    }



}
