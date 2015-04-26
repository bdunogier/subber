Feature: Watch List
  In order to monitor subtitles
  As a REST consumer
  I need to be able to add new episodes to the watch list

  Scenario: Adding a new episode to the watch list works
    Given there is no WatchList item for Release "API test release"
     When I create a JSON payload
      And I set "original_name" to "API test release"
      And I set "path" to "/path/to/api-test-release.mkv"
      And I POST the payload to "/subber/watchlist"
     Then I get a 200 HTTP response
      And there is a WatchList item for Release "API test release"
