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
		$authorId = (isset($request->getParameter('author_id')))?$request->getParameter('author_id'):null;
		$params = array(
			'author_id' => $authorId,
			'article' => $request->getParameter('article')
			);

		$em = $this->getDoctrine()->getManager();

		// save the article
		$saveData = $em->getRepository('AppBundle:Article')->insertData($params);

		// create a notification for the author
		$params['notification'] = '';
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
