<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
	public function testCreateArticle()
	{
		$client = static::createClient();
		$data = json_encode(
			array(
				'author_id' => 1,
				'article' => 'This is a test article written by the test'
			)
		);

		$crawler = $client->request(
			'POST',
			'/api/v1/article/create',
			array('data' => $data)
			);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		
		$response = json_decode($client->getResponse()->getContent(true), true);
		$this->assertArrayHasKey('message', $response);
		$this->assertEquals('success', $response['message']);
	}

	public function testAnswerArticle()
	{
		$client = static::createClient();
		$data = json_encode(
			array(
				'article_id' => 1,
				'answer' => 'This is an answer from the testing suite'
				)
			);
		$crawler = $client->request(
			'POST',
			'/api/v1/article/answer',
			array('data' => $data)
			);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$response = json_decode($client->getResponse()->getContent(true), true);
		$this->assertArrayHasKey('message', $response);
		$this->assertEquals('success', $response['message']);
	}

	public function testRatingArticle()
	{
		$client = static::createClient();
		$data = json_encode(
			array(
				'article_id' => 1,
				'rating' => 5
				)
			);
		$crawler = $client->request(
			'POST',
			'/api/v1/article/rate',
			array('data' => $data)
			);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$response = json_decode($client->getResponse()->getContent(true), true);
		$this->assertArrayHasKey('message', $response);
		$this->assertEquals('success', $response['message']);
	}

	public function testGetArticle()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/api/v1/article/get/1');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$response = json_decode($client->getResponse()->getContent(true), true);
		
		$this->assertArrayHasKey('message', $response);
		$this->assertArrayHasKey('data', $response);

		$this->assertEquals('success', $response['message']);
		
		$this->assertArrayHasKey('article', $response['data']);
		$this->assertArrayHasKey('answers', $response['data']);
		$this->assertArrayHasKey('article', $response['data']['article']);
		$this->assertArrayHasKey('created_on', $response['data']['article']);
	}
}
