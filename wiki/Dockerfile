FROM mediawiki:1.37 AS build

ENV MEDIAWIKI_EXT_BRANCH REL1_37

RUN set -x; \
    apt-get update \
 && apt-get install -y --no-install-recommends \
    git \
    libzip-dev \
    unzip \
    zlib1g-dev \
 && docker-php-ext-install \
    zip \
 && rm -rf /var/lib/apt/lists/*

RUN set -x; \
    apt-get update \
 && apt-get install -y --no-install-recommends \
    libonig-dev \
 && docker-php-ext-install \
    mbstring \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

# Install composer based extensions
RUN cd /var/www/html/ \
 && composer require symfony/css-selector "^3.3" \
 && composer require mediawiki/semantic-media-wiki "~4.0.1" --update-no-dev \
 && composer require mediawiki/semantic-result-formats "~4.0.1" --update-no-dev \
 && composer require mediawiki/semantic-compound-queries "~2.2" --update-no-dev \
 && composer require mediawiki/mermaid "^2.1.1" --update-no-dev \
 && composer require mediawiki/semantic-extra-special-properties "~3.0.1" --update-no-dev \
 && composer require mediawiki/semantic-cite "dev-master" --update-no-dev --prefer-source \
 && composer require mediawiki/semantic-meta-tags "dev-master" --update-no-dev --prefer-source \
 && composer require mediawiki/semantic-breadcrumb-links "dev-master" --update-no-dev --prefer-source \
 && composer require jumbojett/openid-connect-php "0.5.0" --update-no-dev

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

FROM mediawiki:1.37

LABEL vendor='Radio Bern RaBe' \
      maintainer='RaBe IT-Reaktion <it@rabe.ch>'

RUN set -x; \
    apt-get update \
 && apt-get install -y --no-install-recommends \
    librsvg2-bin \
 && rm -rf /var/lib/apt/lists/*

RUN set -x; \
    apt-get update \
 && apt-get install -y --no-install-recommends \
    libonig-dev \
 && docker-php-ext-install \
    mbstring \
 && rm -rf /var/lib/apt/lists/*

COPY --from=build /var/www/html /var/www/html

COPY apache.conf /etc/apache2/conf-enabled/99-wiki.conf

COPY LocalSettings.php /var/www/html/LocalSettings.php 

COPY wiki-cmd.sh /usr/local/bin/wiki-cmd

COPY wiki.png /var/www/html/resources/assets/wiki.png

RUN chown -R www-data: /var/www/html/* 

CMD ["wiki-cmd"]
