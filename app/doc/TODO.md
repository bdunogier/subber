## Things to do and ideas

### Release Parser
- [ ] Release Parser: make sure that the subtitle's extension is given to the parser
- [ ] Release Parser: update namespace, Release\Parser\SubtitlesParser\Addic7ed => Remove 'Parser'
- [ ] Add handling of Proper releases
- [ ] What if a Subtitle's content was a callback, that downloads, and either extracts from the zip file, or
      returns the contents directly ?
- [x] Release Parser: implement dispatcher
- [x] BetaSeries Scrapper: create Subtitle objects using the dispatcher
- [ ] Read extra episode data from the betaseries scrap call, and put those in the Release object

### Other
- [ ] Factorize zip file handling. Consider adding a zipFilename property to the Subtitle object...
- [ ] Store Subber data along with subbed files (.subber folder ? .subber_<filename>.json)
      Or in the cache folder... we might not want to wake the disks up.
      Episode data from betaseries ?
      Matching/Sorting details ?
- [ ] Rename and rework the subtitles collection thing with 3 lists: match, possible
- [ ] Abstract access to stored (cached ? yes, cached. The consumer don't care if it is "stored") data
- [ ] Keep track of the sub that is currently downloaded (and keep its hash, in case it changes...)
- [x] Finish zip file handling, with URI processing. Do we need a local callback ? Or should the downloader take care of it ?
- [x] Add status field to "tasks" table (pending, suggested, confirmed)
- [x] Add a timestamp to tasks
- [x] Parse zip files content
- [x] Abstract subtitle into an object so that we aren't too much tied to betaseries
- [x] Make the script download the best subtitle (if option)
- [x] Treat multiple subs in one zip as variations ? What's the point, just grade them...

### UI
- [ ] Update UI to highlight files where we have subtitles, and say which original file
- [ ] Add a confirm button
- [ ] Offer other files for download

### To consider
- [ ] Add Subber message in post-processing report
- [ ] Search for files without subtitles through folders (what about integrated ones ? mkvinfo ?)
      - [ ] Rebuild the download file's name based on the data in brackets, when possible
