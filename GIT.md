Git on Windows
======================

Create an account on github.
Go to your profile and click on SSH Keys.
We'll be back here later to add your ssh key ;)

Install git
--------

Use http://guides.beanstalkapp.com/version-control/git-on-windows.html as a guide.

Install git: https://code.google.com/p/msysgit/downloads/list?q=full+installer+official+git

Click the option to also run git in command line and in the next step select: Checkout Windows-style, commit Unix-style line endings”.

Generate SSH key
--------
Open GitBash from your start menu/tiles
Fill in: ssh-keygen -t rsa
The default location is good enough.
Don't use a PassPhrase so just push return twice. (even though the guide says to use a strict passphrase)

Go to your .ssh key folder and open id_rsa.pub in notepad.
Copy the whole content of the file.

Go to github > profile > SSH Keys and click on Add ssh key.
Give the title some discriptive name of your pc, so you know where the ssh key came from.
Paste the copied key and click on add key.

You just enabled git on your pc to commit/push to a github repository if you want to.