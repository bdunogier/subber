## To do
- [ ] Store Subber data along with subbed files (.subber folder ? .subber_<filename>.json)
      Episode data from betaseries ?
      Elections results ?
- [ ] Abstract access to stored (cached ? yes, cached. The consumer don't care if it is "stored") data
- [ ] Add status field to "tasks" table (pending, suggested, confirmed)
- [ ] Update UI to highlight files where we have subtitles, and say which original file
- [ ] Keep track of the sub that is currently downloaded (and keep its hash, in case it changes...)
- [ ] Add a confirm button
- [ ] Offer other files for download
- [x] Parse zip files content
- [x] Abstract subtitle into an object so that we aren't too much tied to betaseries
- [x] Make the script download the best subtitle (if option)
- [x] Treat multiple subs in one zip as variations ? What's the point, just grade them...

## To consider
- [ ] Add Subber message in post-processing report
- [ ] Search for files without subtitles through folders (what about integrated ones ? mkvinfo ?)
      - [ ] Rebuild the download file's name based on the data in brackets, when possible
