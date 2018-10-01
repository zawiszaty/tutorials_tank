Feature: User Feature
  Scenario: Adding new User
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/user/register" with body:
    """
    {
	"email": "test13@wp.pl",
	"username": "test14",
	"plainPassword": {
		"first": "test",
		"second": "test"
		}
    }
    """
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    Then the response status code should be 200

  Scenario: Banned User
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I add "Authorization" header equal to "Bearer AdminTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg"
    And I send a "POST" request to "/api/v1/user/banned/127c6fd0-be8d-11e8-a355-529269fb1459"
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    Then the response status code should be 200

  Scenario: Confirm User
    When i have user in database
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/user/confirm/124"
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    Then the response status code should be 200