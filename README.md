# docker-matomo

Matomo does that web tracker analytics thing
that you'd like to use Google Analytics for but
you don't want to give them all your data.

## How it works

You put a block of JavaScript into every page you want tracked,
and that causes the browser to send information to Matomo
and store it in a database.

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

Matomo is behind a reverse proxy, so all traffic appears to come from the proxy server, and therefore, the local network. Since everything is on Docker that means a 172 address.

To see what's going on, I started a bash shell and edited /etc/apache2/apache2.conf to add this line after the place where modules are loaded.

```bash
ForensicLog /tmp/forensic.log
```

Then I enabled to module, and restart Apache.
This will also kick me out of the bash shell, so the third 
command will work.

```bash
a2enmod log_forensic
apachectl restart
docker exec matomo_app_1 tail -f /tmp/forensic.log
```

Then I can capture a couple 'wgets', one from inside and one from outside to see the headers.

```bash
+863:6234b92d:11|GET /no.php HTTP/1.1|Host:echo.co.clatsop.or.us|Connection:close|X-Real-IP:172.18.0.1|X-Forwarded-For:172.18.0.1|X-Forwarded-Proto:https|X-Forwarded-Ssl:on|X-Forwarded-Port:443|User-Agent:Wget/1.20.1 (linux-gnu)|Accept:*/*|Accept-Encoding:identity
-863:6234b92d:11

+867:6234b911:10|GET /no.php HTTP/1.1|Host:echo.co.clatsop.or.us|Connection:close|X-Real-IP:47.33.165.207|X-Forwarded-For:47.33.165.207|X-Forwarded-Proto:https|X-Forwarded-Ssl:on|X-Forwarded-Port:443|User-Agent:Wget/1.20.3 (linux-gnu)|Accept:*/*|Accept-Encoding:identity
-867:6234b911:10
```

I can use this information when I build the htaccess file, and I can turn the forensic log off by simply reloading the docker, since the changes I
made are not in the image they will disappear.

## LICENSE

[MIT](LICENSE)
