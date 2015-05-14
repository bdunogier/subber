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
      And a WatchList item was created for Release "API test release"

  Scenario: The dashboard shows WatchList items

  Scenario: The dashboard shows the currently playing WatchList item

  Scenario: The monitor checks a new WatchList item for subtitles
    #Given there is a WatchList item for Release "API test release"
     #When I execute the WatchList Monitor
     #Then I see subtitles checked for "API test release"
