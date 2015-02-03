# gifv short code for WordPress

Provides a `[gifv]` shortcode to display videos in a gif like fashion in WordPress.

Hows it different from the `[video]` shortcode:

- Loop is on by default
- Autoplay is set true
- Controls are hidden
- Audio is muted
- Can have multiple videos on at the same time
- No external JavaScript included

## Usage

Basic usage:

```
[gifv width="250" height="250" webm="http://clubmate.fi/content/uploads/man.webm"][/gifv]
```

Usage with mp4 fallback:

```
[gifv width="250" height="250" webm="http://clubmate.fi/content/uploads/man.webm" mp4="http://clubmate.fi/content/uploads/man.mp4"][/gifv]
```

If you upload videos to WP and insert them to the post, they will be inserted using the `[video]` shortcode, just change `video` to `gifv`.

Note that WP nor this plugin doesn't handle gif > video conversion, you have to do that yourself. See my blog post about it.

## Note worthy things

I recommend using WebM as a format, it open source and quite alright. But, mp4 has a wider browser support, so it's up to you. Or use WebM and mp4 as a fall back.