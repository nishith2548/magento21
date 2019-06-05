FROM deepu157915/magento21

LABEL maintainer="Chandradeep"

ENV MAGENTO_VERSION 2.1.17
ENV INSTALL_DIR /var/www/html


#COPY ./auth.json $COMPOSER_HOME

RUN chsh -s /bin/bash www-data

#RUN cd /var/www/html/magento21
#RUN git pull origin master
#RUN cd /tmp && \ 
 # curl https://codeload.github.com/magento/magento2/tar.gz/$MAGENTO_VERSION -o $MAGENTO_VERSION.tar.gz && \
  #tar xvf $MAGENTO_VERSION.tar.gz && \
  #mv magento2-$MAGENTO_VERSION/* magento2-$MAGENTO_VERSION/.htaccess $INSTALL_DIR

RUN chown -R www-data:www-data /var/www
#RUN su www-data -c "cd $INSTALL_DIR && composer install"
#RUN su www-data -c "cd $INSTALL_DIR && composer config repositories.magento composer https://repo.magento.com/"  

RUN cd $INSTALL_DIR \
    && find . -type d -exec chmod 770 {} \; \
    && find . -type f -exec chmod 660 {} \; 

#COPY ./install-magento /usr/local/bin/install-magento
#RUN chmod +x /usr/local/bin/install-magento

#COPY ./install-sampledata /usr/local/bin/install-sampledata
#RUN chmod +x /usr/local/bin/install-sampledata
RUN a2enmod rewrite
RUN echo "memory_limit=2048M" > /usr/local/etc/php/conf.d/memory-limit.ini

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR $INSTALL_DIR

# Add cron job
# ADD crontab /etc/cron.d/magento2-cron
# RUN chmod 0644 /etc/cron.d/magento2-cron \
#    && crontab -u www-data /etc/cron.d/magento2-cron
