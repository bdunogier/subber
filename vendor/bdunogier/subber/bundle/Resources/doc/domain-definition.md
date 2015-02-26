A Scrapper finds subtitles for a TV episode on the Source it Scraps.

It returns a list of Subtitle Scraps. A Subtitle Scrap contains results from a Scrap:
- a (downloadable) link to a subtitle file,
- a few metadata about the subtitle (name on the site of any, maybe filename itself, language if applicable...).

A Subtitle Downloader can be fed with a Subtitle Scrap, and download the file from it.

A set of Subtitle Scraps can be Elected by Subtitle Voters. A Subtitle Voter will, based on each Subtitle Scrap metadata,
Vote each of them. Votes can be For, Against or Abstention. Subtitle with Against Votes are by default elimited, but
this could vary depending on the Voting methodology.
