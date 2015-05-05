Feature: SickBeard wrapper

  Scenario: The wrapper adds to the WatchList a successfully post-processed TV Episode release
    Given that the Release "My.test.release-subber" will be moved to "/path/to/My test release.mkv" by the post-processor
     When the SickBeard Wrapper is executed
     Then post-processing is successful
      And there is a WatchList item for Release "My.test.release-subber" and file "/path/to/My test release.mkv"
