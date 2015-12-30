<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answers
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AnswersRepository")
 */
class Answers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="article_id", type="integer")
     */
    private $articleId;

    /**
     * @var string
     *
     * @ORM\Column(name="article_answer", type="text")
     */
    private $articleAnswer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set articleId
     *
     * @param integer $articleId
     *
     * @return Answers
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return integer
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set articleAnswer
     *
     * @param string $articleAnswer
     *
     * @return Answers
     */
    public function setArticleAnswer($articleAnswer)
    {
        $this->articleAnswer = $articleAnswer;

        return $this;
    }

    /**
     * Get articleAnswer
     *
     * @return string
     */
    public function getArticleAnswer()
    {
        return $this->articleAnswer;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Answers
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }
}

