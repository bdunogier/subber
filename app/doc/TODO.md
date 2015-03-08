## Things to do and ideas

### Release Parser
- [ ] Release Parser: make sure that the subtitle's extension is given to the parser
- [ ] What if a Subtitle's content was a callback, that downloads, and either extracts from the zip file, or
      returns the contents directly ?
- [ ] Check proper in Matcher. Find a way to "tolerate" subtitles that aren't marked as proper with proper releases.
      Ideally, this would only be triggered if there are no Proper Subtitles, as it would indicate that the Proper
      does not affect subtitles.
- [ ] Rethink subtitles matching a bit. If a subtitle specifies source+resolution, it means it only applies to this
      source AND resolution, does it not ?

### Application/architecture
- [ ] RateSubtitleEvent when a subtitle gets rated
- [ ] Rename 'Video' to 'Episode'

### Other
- [ ] Compatible groups are a bit of an issue.
      lol is usually compatible with dimension *only* if one of the two releases doesn't have an explicit subtitle.
      This could only be done if the subtitle Matcher received the whole list, and had higher level information.
      Kind of a multiple pass matching... ?
- [ ] Change Commands to commands as a service
- [ ] Factorize zip file handling. Consider adding a zipFilename property to the Subtitle object...
- [ ] Rename and rework the subtitles collection thing with 3 lists: match, possible

### UI
- [ ] Update UI to highlight files where we have subtitles, and say which original file
- [ ] Add a confirm button
- [ ] Offer other files for download

### To consider
- [ ] Add Subber message in post-processing report
- [ ] Search for files without subtitles through folders (what about integrated ones ? mkvinfo ?)
      - [ ] Rebuild the download file's name based on the data in brackets, when possible

## Done
- [x] Add handling of Proper releases
- [x] Read extra episode data from the betaseries scrap call, and put those in the Release object
- [x] Release Parser: update namespace, Release\Parser\SubtitlesParser\Addic7ed => Remove 'Parser'
- [x] Add "The Simpsons S24E17 1080p WEB-DL H 264 DD5 1-NTb" to tests
- [x] Release Parser: implement dispatcher
- [x] BetaSeries Scrapper: create Subtitle objects using the dispatcher
- [x] Abstract access to stored (cached ? yes, cached. The consumer don't care if it is "stored") data
- [ ] Keep track of the sub that is currently downloaded (and keep its hash, in case it changes...)
- [x] ~Store Subber data along with subbed files (.subber folder ? .subber_<filename>.json)~
      Or in the cache folder... we might not want to wake the disks up. Cached with stash.
      Episode data from betaseries ?
      Matching/Sorting details ?
- [x] Finish zip file handling, with URI processing. Do we need a local callback ? Or should the downloader take care of it ?
- [x] Add status field to "tasks" table (pending, suggested, confirmed)
- [x] Add a timestamp to tasks
- [x] Parse zip files content
- [x] Abstract subtitle into an object so that we aren't too much tied to betaseries
- [x] Make the script download the best subtitle (if option)
- [x] Treat multiple subs in one zip as variations ? What's the point, just grade them...
- [x] ScrapReleaseEvent when a release gets scrapped for subtitles
- [x] QueueTaskEvent when a task gets queued
