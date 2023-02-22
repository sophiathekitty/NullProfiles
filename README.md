# Null Profiles

this plugin handles user profiles and preferences.

## clone repo

```bash
cd /var/www/html/plugins
```

```bash
git clone https://github.com/sophiathekitty/NullProfiles.git
```

make sure to create ```img/users/``` folder and make sure php can write to it. for uploading image.

### setup cron job

```bash
sudo crontab -e
```

```Apache config
3 * * * * sh /var/www/html/plugins/NullProfiles/gitpull.sh
```

* icons made with [open source icons](https://game-icons.net/)
