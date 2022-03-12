# docker-matomo

Matomo does the web tracker analytics thing
that you'd like to use Google Analytics for but
you don't want to give them all your data.

## How it works

You put a bit of JavaScript into every page you want tracked,
and this thing catches the hits and stores them in a database.

## Deploy

This project has its very own web server running at
http://webforms.co.clatsop.or.us/. To start it, do this,

```bash
docker stack deploy -c docker-compose.yml matomo
```

## GeoIP

Getting access to the GeoIP database requires effort I have yet to expend.

https://blog.maxmind.com/2019/12/significant-changes-to-accessing-and-using-geolite2-databases

## Tracking code

This is the code to put in pages you want to track on Delta.

<!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//webforms.co.clatsop.or.us/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->

## LICENSE

[MIT](LICENSE)
