<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
	/**
	 * @Route("/api/v1/article/create")
	 */
	public function createArticleAction(Request $request)
	{
		/**
		 * The information needed:
		 * . The article
		 * . The author
		 * . Create a notification for the author
		 */
		$authorId = (isset($request->getParameter('author_id')))?$request->getParameter('author_id'):false;
		if(!$authorId) {
			$response = new JsonResponse();
			$response->setData(array(
				'message' => 'error',
				'data' => 'author id not set'
				));
			return $response;
		}

		$params = array(
			'author_id' => $authorId,
			'article' => $request->getParameter('article')
			);

		$em = $this->getDoctrine()->getManager();

		// save the article
		$saveData = $em->getRepository('AppBundle:Article')->createArticle($params);

		// create a notification for the author
		$params['notification'] = 'A new article has been created';
		$notification = $em->getRepository('AppBundle:Notification')->createNotification($params);

		$response = new JsonResponse();
		$response->setData(array(
			'message' => 'success'
			));

		return $response;
	}

	/**
	 * @Route("/api/v1/article/answer")
	 */
	public function answerArticleAction(Request $request)
	{
		$articleId = (isset($request->getParameter('article_id')))?$request->getParameter('article_id'):false;
		if(!$articleId) {
			$response = new JsonResponse();
			$response->setData(array(
				'message' => 'error',
				'data' => 'article id not set'
				));
			return $response;
		}

		$params = array(
			'article_id' => $articleId,
			'answer' => $response->getParameter('answer')
			);
		$em = $this->getDoctrine()->getManager();

		// save the answer
		$answer = $em->getRepository('AppBundle:Answer')->saveAnswer($params);

		// get the author info
		$authorId = $em->getRepository('AppBundle:Article')->getAuthorId($articleId);
		if($authorId) {
			$params = array(
				'author_id' => $authorId,
				'notification' => 'You have a response to your article'
				);
			$notification = $em->getRepository('AppBundle:Notification')->createNotification($params);
		}

		$response = new JsonResponse();
		$response->setData(array(
			'message' => 'success'
			));

		return $response;
	}

	/**
	 * @Route("/api/v1/article/rate")
	 */
	public function rateArticleAction(Request $request)
	{

	}

	/**
	 * @Route("/api/v1/article/get")
	 */
	public function getArticleAction(Request $request)
	{
		// also needs to retrieve all its answers
	}
}
