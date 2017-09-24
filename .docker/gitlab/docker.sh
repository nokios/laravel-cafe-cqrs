sudo docker run --detach \
    --hostname gitlab.nokiosdesigns.com \
    --expose 443 --expose 80 --publish 2222:22 \
    --name gitlab \
    --restart always \
    --volume "$(pwd)/srv/gitlab/config:/etc/gitlab" \
    --volume "$(pwd)/srv/gitlab/logs:/var/log/gitlab" \
    --volume "$(pwd)/srv/gitlab/data:/var/opt/gitlab" \
    --env "VIRTUAL_HOST=gitlab.nokiosdesigns.com" \
    --env "LETSENCRYPT_HOST=gitlab.nokiosdesigns.com" \
    --env "LETSENCRYPT_EMAIL=trenton.craig@gmail.com" \
    gitlab/gitlab-ce:latest