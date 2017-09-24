FROM laradock/workspace:1.8-71

ARG PUID=1000
ARG PGID=1000

RUN groupadd -g ${PGID} laradock && \
    useradd -u ${PUID} -g laradock -m laradock && \
    apt-get update -yqq

ARG TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ARG NODE_VERSION=stable
ARG INSTALL_NODE=true
ENV NVM_DIR /home/laradock/.nvm

RUN if [ ${INSTALL_NODE} = true ]; then \
    # Install nvm (A Node Version Manager)
    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.1/install.sh | bash && \
        . $NVM_DIR/nvm.sh && \
        nvm install ${NODE_VERSION} && \
        nvm use ${NODE_VERSION} && \
        nvm alias ${NODE_VERSION} && \
        npm install -g gulp bower vue-cli \
;fi

USER laradock

# Wouldn't execute when added to the RUN statement in the above block
# Source NVM when loading bash since ~/.profile isn't loaded on non-login shell
RUN if [ ${INSTALL_NODE} = true ]; then \
    echo "" >> ~/.bashrc && \
    echo 'export NVM_DIR="$HOME/.nvm"' >> ~/.bashrc && \
    echo '[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"  # This loads nvm' >> ~/.bashrc \
;fi

# Add NVM binaries to root's .bashrc
USER root

RUN if [ ${INSTALL_NODE} = true ]; then \
    echo "" >> ~/.bashrc && \
    echo 'export NVM_DIR="/home/laradock/.nvm"' >> ~/.bashrc && \
    echo '[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"  # This loads nvm' >> ~/.bashrc \
;fi

# Add PATH for node
ENV PATH $PATH:$NVM_DIR/versions/node/v${NODE_VERSION}/bin

USER root
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www