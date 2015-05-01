## Things to do and ideas

## Bugs
- [ ] Fix empty URL In subtitle object, in BasicSaver

## Releases parsing

### Subtitles parsing use-cases
- [ ] tvsubtitles "12 Monkeys - 1x11 - Shonin.HDTV.ASAP.en.srt" ends up with two groups (null + asap)
- [ ] seriessub "The.Walking.Dead.513.KILLERS.VF.srt.ass" isn't parsed correctly (group is set to vf)
- [ ] "A scrapping error occured on Once.Upon.a.Time.S04E14.720p.HDTV.x264-KILLERS.mkv: Aucun épisode trouvé"
- [ ] Addic7ed: "A scrapping error occured on 12.Monkeys.S01E07.The.Keys.720p.WEB-DL.DD5.1.H.264-BS.mkv: Parsing error: Unable to parse '12 Monkeys - 01x07 - The Keys.srt': addic7ed.com string not found"

### Releases matching use-cases
- [ ] Subtitle addic7ed "Vikings - 03x06 - Born Again.KILLERS.French.C.updated.Addic7ed.com.srt" is not
      compatible with "vikings.s03e06.720p.hdtv.x264-killers"
- [ ] Subtitles for "greys.anatomy.s11e13.720p.hdtv.x264-dimension" are not found: mp4 subs are probably compatible...
- [ ] Release Parser: make sure that the subtitle's extension is given to the parser (should be ok on some)

### Specs to write
- [ ] BD\Subber\ReleaseSubtitles\Index
- [ ] BD\Subber\Subtitles\Saver\BasicSaver

### Command Line
- [ ] Add file/release filtering to subber:watchlist:show, and remove the dedicated commands

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

### UI
- [ ] Add a download subtitle button to the subtitle view, to download either the best sub (+ others)
- [ ] Update UI to highlight files where we have subtitles, and say which original file
- [ ] Add a confirm button
- [ ] Offer other files for download

### To consider
- [ ] Add Subber message in post-processing report
- [ ] Search for files without subtitles through folders (what about integrated ones ? mkvinfo ?)
      - [ ] Rebuild the download file's name based on the data in brackets, when possible

## Done
- [x] Rename and rework the subtitles collection thing with 3 lists: match, possible
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
- [x] Handle addic7ed Subtitle "The Big Bang Theory - 08x18 - The Leftover Thermalization.DIMENSION.colored.English.HI.C.orig.Addic7ed.com.srt"
      (parses 'colored' as the group name)
- [x] Handle tvsubtitles Subtitle "Person of Interest - 4x17 - Karma.HDTV.LOL+720p.DIMENSION+AFG+mSD.fr.srt"

### Index
Should the Index do more ? Could the Factory take care of scrapping, and send the "raw" data to the Index ?
Maybe some kind of event could be added... a

### Subtitle Matching Context
Each episode release has its own Context, that is hard to determine based on a subtitle release or episode release alone.

If we have a Repack Release or a Proper Release, does it affect the subtitles ?
Are the 720p and 1080p hdtv releases compatible ?

This Context can be built based on the Episode Release + Subtitle Releases list, and passed during to the Matcher.

