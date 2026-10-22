ARG PHP_VERSION=8.2.35

FROM php:${PHP_VERSION}-apache AS build

ENV MEDIAWIKI_EXT_BRANCH=REL1_37
ENV MEDIAWIKI_VERSION=1.37.6
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -x; \
  apt-get update \
  && apt-get install -y --no-install-recommends \
  git \
  libicu-dev \
  libzip-dev \
  unzip \
  zlib1g-dev \
  && docker-php-ext-install -j "$(nproc)" \
  calendar \
  intl \
  zip \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# MediaWiki core: same release as before the PHP move, taken from the upstream
# release tarball rather than from the (unpatched) mediawiki image.
WORKDIR /var/www/html
RUN set -x; \
  curl -fSL "https://releases.wikimedia.org/mediawiki/${MEDIAWIKI_VERSION%.*}/mediawiki-${MEDIAWIKI_VERSION}.tar.gz" \
  -o /tmp/mediawiki.tar.gz \
  && tar -x --strip-components=1 -f /tmp/mediawiki.tar.gz -C /var/www/html \
  && rm /tmp/mediawiki.tar.gz \
  && chown -R www-data:www-data /var/www/html

# Install composer based extensions.
#
# Semantic MediaWiki has to be >= 4.2: SMW 4.0.x registers itself through
# Setup::initExtension( array &$vars ), which PHP 8 rejects outright
# ("Argument #1 ($vars) cannot be passed by reference"). SMW 4.2 still declares
# "MediaWiki >= 1.35", so MediaWiki core stays at 1.37.6.
#
# -W (--with-all-dependencies) is required: without it composer keeps
# composer/installers pinned to the version locked by the first require, and
# mediawiki/semantic-compound-queries 2.2 needs composer/installers 1.*.
# composer >= 2.7 additionally refuses packages that carry security advisories,
# which MediaWiki 1.37's pinned dev-era dependencies do, hence --no-blocking
# --no-audit on every require.
#
# SMW 4.0.x is not an option on PHP 8: it registers itself through
# Setup::initExtension( array &$vars ), which PHP 8 rejects outright
# ("Uncaught Error: SMW\Setup::initExtension(): Argument #1 ($vars) cannot be
# passed by reference in includes/SemanticMediaWiki.php:46"). SMW 4.2 still
# declares "MediaWiki >= 1.35", so MediaWiki core stays at 1.37.6.
RUN cd /var/www/html/ \
  && composer require --no-blocking --no-audit -W symfony/css-selector "^3.3" \
  && composer require --no-blocking --no-audit -W mediawiki/semantic-media-wiki "~4.2" --update-no-dev \
  && composer require --no-blocking --no-audit -W mediawiki/semantic-result-formats "~4.0.1" --update-no-dev \
  && composer require --no-blocking --no-audit -W mediawiki/semantic-compound-queries "~2.2" --update-no-dev \
  && composer require --no-blocking --no-audit -W mediawiki/mermaid "^2.1.1" --update-no-dev \
  && composer require --no-blocking --no-audit -W mediawiki/semantic-extra-special-properties "~3.0.1" --update-no-dev \
  && composer require --no-blocking --no-audit -W mediawiki/semantic-cite "dev-master" --update-no-dev --prefer-source \
  && composer require --no-blocking --no-audit -W mediawiki/semantic-meta-tags "dev-master" --update-no-dev --prefer-source \
  && composer require --no-blocking --no-audit -W mediawiki/semantic-breadcrumb-links "dev-master" --update-no-dev --prefer-source \
  && composer require --no-blocking --no-audit -W jumbojett/openid-connect-php "0.5.0" --update-no-dev

# Install extensions with a git based install
RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/ExternalData.git /var/www/html/extensions/ExternalData

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/SemanticInternalObjects.git /var/www/html/extensions/SemanticInternalObjects

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/SemanticDrilldown.git /var/www/html/extensions/SemanticDrilldown

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/PageForms.git /var/www/html/extensions/PageForms

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/Arrays.git /var/www/html/extensions/Arrays

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/HeaderTabs.git /var/www/html/extensions/HeaderTabs

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/ApprovedRevs.git /var/www/html/extensions/ApprovedRevs

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/Auth_remoteuser.git /var/www/html/extensions/Auth_remoteuser

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/HierarchyBuilder.git /var/www/html/extensions/HierarchyBuilder

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/skins/Material.git /var/www/html/skins/Material

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/PluggableAuth.git /var/www/html/extensions/PluggableAuth

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/OpenIDConnect.git /var/www/html/extensions/OpenIDConnect \
  && sed -i "s/\$oidc->requestUserInfo( 'sub' );/\$oidc->requestUserInfo( \$GLOBALS['wgOpenIDConnect_SubjectUserInfoClaim'] );/" /var/www/html/extensions/OpenIDConnect/src/OpenIDConnect.php

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/UserMerge.git /var/www/html/extensions/UserMerge

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/NativeSvgHandler.git /var/www/html/extensions/NativeSvgHandler

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/StopForumSpam.git /var/www/html/extensions/StopForumSpam

RUN git clone --depth=1 -b $MEDIAWIKI_EXT_BRANCH https://gerrit.wikimedia.org/r/mediawiki/extensions/SmiteSpam.git /var/www/html/extensions/SmiteSpam

FROM php:${PHP_VERSION}-apache

LABEL vendor='Radio Bern RaBe' \
  maintainer='RaBe IT-Reaktion <it@rabe.ch>'

# Runtime dependencies only: the PHP extensions MediaWiki needs on top of the
# php-*-apache base (that base already provides mbstring, dom, xml, curl and
# OPcache, but no mysqli and no intl). MediaWiki 1.37's composer.json also
# requires ext-calendar and ext-intl, so both are compiled here and in the build
# stage. libicu-dev is build-only and is dropped again after intl is compiled.
RUN set -x; \
  apt-get update \
  && apt-get install -y --no-install-recommends \
  librsvg2-bin \
  imagemagick \
  libicu-dev \
  && docker-php-ext-install -j "$(nproc)" \
  calendar \
  intl \
  mysqli \
  && apt-get purge -y libicu-dev \
  && rm -rf /var/lib/apt/lists/*

COPY --from=build /var/www/html /var/www/html

# wiki-cmd.sh runs `php maintenance/update.php` relative to the work dir
WORKDIR /var/www/html

# apache.conf uses FallbackResource, which is provided by mod_rewrite
RUN a2enmod rewrite

COPY apache.conf /etc/apache2/conf-enabled/99-wiki.conf

COPY LocalSettings.php /var/www/html/LocalSettings.php

COPY wiki-cmd.sh /usr/local/bin/wiki-cmd

COPY wiki.png /var/www/html/resources/assets/wiki.png

RUN chown -R www-data: /var/www/html/*

CMD ["wiki-cmd"]
