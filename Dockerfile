FROM php:8.2-apache

# --- Extensions PHP ---
# pdo, pdo_mysql et calendar (pour cal_days_in_month)
RUN docker-php-ext-install \
        pdo \
        pdo_mysql \
        calendar

# --- Xdebug (facultatif) ---
# Supprime un éventuel Xdebug déjà activé
RUN rm -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini || true

# Installe Xdebug seulement s’il n’est pas encore présent
RUN if ! php -m | grep -iq xdebug; then \
        pecl install xdebug && docker-php-ext-enable xdebug; \
    else \
        echo "Xdebug déjà installé, on passe."; \
    fi

# Copie de la configuration Xdebug (une seule fois)
COPY ./php/conf/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# --- Apache ---
RUN a2enmod rewrite
