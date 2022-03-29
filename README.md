# docker-matomo

Matomo does that web tracker analytics thing
that you'd like to use Google Analytics for but
you don't want to give them all your data.

## How it works

You put a block of JavaScript into every page you want tracked,
and that causes the browser to send information to Matomo
and store it in a database.

## Configure

Copy sample.env to .env and edit it.
Set a static ip address that matches the one used by the reverse proxy.

## Deploy

The folders db and matomo are used by the bitnami dockers
so they have to be owned by UID=1001

This project has a web server running at
http://webforms.co.clatsop.or.us/. To start it, do this,

```bash
docker-compose up -d
```

## GeoIP

You used to be able to just download free data files, privacy concerns
mean you have to create a MaxMind.com account.

Login and then dig around 
in the developer section until you find and download a copy of the city data.

Unpack it and put a copy of the GeoLite2_City.mmdb file 
in /var/lib/docker/volumes/matomo_matomo/_data/misc/

https://blog.maxmind.com/2019/12/significant-changes-to-accessing-and-using-geolite2-databases

The Dockerfile in build_context will download, compile, and install
the Apache module to read the database.

You have to tell Apache to load the module and use it. The
easiest way to do that is to make sure there is a copy of the .htaccess
file in matamo_app/.

## Tracking code

This is the code to put in pages you want to track on Delta.

<!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="https://echo.co.clatsop.or.us/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->

## Securing the administrative server

I want people on the 'net to be able to see the tracker, obviously, 
but I don't want them to have access to the admin web pages.

Matomo is behind a reverse proxy, so all traffic appears to come from the proxy server, and therefore, the local network. In order to block outside
traffic, you have to watch the headers that the proxy inserts.

When you do a 'curl https://echo.co.clatsop.or.us/no.php' internally
it will work. When you do it from a docker machine or from outside it
will fail.

You can use the Apache forensic log but using PHP is easier.

Copy htaccess to matomo_app/.htaccess and it should start working.
Uncomment the "no.php" section and hit that URL and you should see
the full headers similar to this

```bash
1647628471.<br>Host: echo.co.clatsop.or.us
Connection: close
X-Real-IP: 47.33.165.207
X-Forwarded-For: 47.33.165.207
X-Forwarded-Proto: https
X-Forwarded-Ssl: on
X-Forwarded-Port: 443
user-agent: curl/7.68.0
accept: */*
```

## LICENSE

[MIT](LICENSE)
