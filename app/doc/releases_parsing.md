One of the key points of this application is the parsing of everything into Releases.

A Release object contains semantic information, build from the release's name. This is meant to make it easy to
determine compatibility of Subs with Episodes.

# Type

## Episodes

## Subtitles
As most sources use their own naming, subtitles parsing depends on the source.

## Matching
Subtitle and Episode Releases are meant to be Matched for Compatibility.

### Multi-target subtitles
It is quite common that subtitles will be compatible across releases, usually when the same source was used.

One key difficulty is that some names mixup information. For instance, the Subtitle Release
`The Simpsons.S24E17.What Animated Women Want.LOL.720p` says Group LOL, but Resolution 720p. LOL only does 480p
releases. This would mean that this Subtitle was written for the LOL Episode Release, but also works for the 720p one
(whoever Released it).

This particular use-case is not implemented yet.

**Possible solution**

One way would be to create two Subtitle Release objects, one with
`group` set to `lol` + resolution `480p` (lol only does 480p), and the other with `resolution` set to `720p`, and the
group unset.
