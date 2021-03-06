<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
	/**
	 * @Route("/api/v1/article/create")
	 * @Method({"POST"})
	 */
	public function createArticleAction(Request $request)
	{
		$json = json_decode($request->request->get('data'), true);
		$authorId = $json['author_id'];
		$response = new JsonResponse();
		if(!$authorId) {
			$response->setData(array(
				'message' => 'error',
				'data' => 'author id not set'
				));
			return $response;
		}

		$params = array(
			'author_id' => $authorId,
			'article' => $json['article']
			);

		$em = $this->getDoctrine()->getManager();

		// save the article
		$saveData = $em->getRepository('AppBundle:Article')->createArticle($params);
		if(!$saveData) {
			$response->setData(array(
				'message' => 'error',
				'data' => 'There was a problem saving the article'
				));
			return $response;
		}

		// create a notification for the author
		$params['notification'] = 'A new article has been created';
		$notification = $em->getRepository('AppBundle:Notification')->createNotification($params);

		$response->setData(array(
			'message' => 'success'
			));

		return $response;
	}

	/**
	 * @Route("/api/v1/article/answer")
	 * @Method({"POST"})
	 */
	public function answerArticleAction(Request $request)
	{
		$json = json_decode($request->request->get('data'), true);
		$response = new JsonResponse();
		$articleId = $json['article_id'];
		if(!$articleId) {
			$response->setData(array(
				'message' => 'error',
				'data' => 'article id not set'
				));
			return $response;
		}

		$params = array(
			'article_id' => $articleId,
			'answer' => $json['answer']
			);
		$em = $this->getDoctrine()->getManager();

		// save the answer
		$answer = $em->getRepository('AppBundle:Answers')->saveAnswer($params);

		if(!$answer) {
			$response->setData(array(
				'message' => 'error',
				'data' => 'There was a problem saving the answer'
				));
			return $response;
		}

		// get the author info
		$authorId = $em->getRepository('AppBundle:Article')->getAuthorId($articleId);
		if($authorId) {
			$params = array(
				'author_id' => $authorId,
				'notification' => 'You have a response to your article'
				);
			$notification = $em->getRepository('AppBundle:Notification')->createNotification($params);
		}

		$response->setData(array(
			'message' => 'success'
			));

		return $response;
	}

	/**
	 * @Route("/api/v1/article/rate")
	 * @Method({"POST"})
	 */
	public function rateArticleAction(Request $request)
	{
		$json = json_decode($request->request->get('data'), true);
		$response = new JsonResponse();
		$articleId = $json['article_id'];
		$starRating = $json['rating'];
		if(!$articleId || $starRating < 0 || $starRating > 5 || !is_numeric($starRating)) {
			$response->setData(array(
				'message' => 'error',
				'data' => 'article id not set or rating not in correct format'
				));
			return $response;
		}

		$em = $this->getDoctrine()->getManager();

		$params = array(
			'article_id' => $articleId,
			'rating' => $starRating,
			);

		// save the rating
		$rating = $em->getRepository('AppBundle:Rating')->saveRating($params);
		if(!$rating) {
			$response->setData(array(
				'message' => 'error',
				'data' => 'there was a problem saving the rating'
				));
			return $response;
		}

		// get the author info
		$authorId = $em->getRepository('AppBundle:Article')->getAuthorId($articleId);
		if($authorId) {
			$params = array(
				'author_id' => $authorId,
				'notification' => 'You have a rating for your article'
				);
			$notification = $em->getRepository('AppBundle:Notification')->createNotification($params);
		}

		$response->setData(array(
			'message' => 'success'
			));

		return $response;
	}

	/**
	 * @Route("/api/v1/article/get/{articleId}")
	 * @Method({"GET"})
	 */
	public function getArticleAction($articleId)
	{
		$response = new JsonResponse();
		$article = array(); // set a default value
		$answers = array(); // set a default value

		if($articleId < 1) {
			$response->setData(array(
				'message' => 'error',
				'data' => 'article id not set'
				));
			return $response;
		}

		$em = $this->getDoctrine()->getManager();
		$article = $em->getRepository('AppBundle:Article')->getArticle($articleId);
		if(!$article) {
			$response->setData(array(
				'message' => 'error',
				'data' => 'article not found'
				));
			return $response;
		}

		$getAnswers = $em->getRepository('AppBundle:Answers')->getAnswersByArticle($articleId);
		$answers = (!$getAnswers)?$answers:$getAnswers;

		$response->setData(array(
			'message' => 'success',
			'data' => array(
				'article' => $article,
				'answers' => $answers
				)
			));

		return $response;
	}
}
