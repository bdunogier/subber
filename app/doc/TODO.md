## Things to do and ideas

### Release Parser
- [x] Handle addic7ed Subtitle "The Big Bang Theory - 08x18 - The Leftover Thermalization.DIMENSION.colored.English.HI.C.orig.Addic7ed.com.srt"
      (parses 'colored' as the group name)
- [x] Handle tvsubtitles Subtitle "Person of Interest - 4x17 - Karma.HDTV.LOL+720p.DIMENSION+AFG+mSD.fr.srt"
- [ ] Release Parser: make sure that the subtitle's extension is given to the parser (should be ok on some)
- [ ] What if a Subtitle's content was a callback, that downloads, and either extracts from the zip file, or
      returns the contents directly ?

### Subtitle Matching Context
Each episode release has its own Context, that is hard to determine based on a subtitle release or episode release alone.

If we have a Repack Release or a Proper Release, does it affect the subtitles ?
Are the 720p and 1080p hdtv releases compatible ?

This Context can be built based on the Episode Release + Subtitle Releases list, and passed during to the Matcher.

### SubtitleReleaseList Consolidator
Consolidates a list of Subtitle Releases (before Indexing).
The goal is to get Subtitles with properties we can rely upon during Matching.

- [ ] SHOULD be refactored so that each operation has its own Consolidator
- [ ] SHOULD offer parameters to set release group properties (resolution, source, ...)
- [x] SHOULD Fork Subtitles that have array properties (multiple resolutions/groups)
- [x] SHOULD Fork Subtitles or inconsistent properties (lol.720p)
- [ ] COULD complete properties with known data: lol = 480p, dimension = 720p (not sure it's actually a good idea)

### Application/architecture
- [ ] RateSubtitleEvent when a subtitle gets rated
- [ ] Rename 'Video' to 'Episode'

### Other
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
- [x] Write Matcher tests: EpisodeRelease + SubtitleRelease, and expected result (true/false)
- [x] Check proper in Matcher. Find a way to "tolerate" subtitles that aren't marked as proper with proper releases.
      Ideally, this would only be triggered if there are no Proper Subtitles, as it would indicate that the Proper
      does not affect subtitles.
- [x] Rethink subtitles matching a bit. If a subtitle specifies source+resolution, it means it only applies to this
      source AND resolution, does it not ?
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
