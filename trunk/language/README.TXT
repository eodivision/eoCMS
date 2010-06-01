Creating a new language:

The language folder is a 2 lettered folder name following the international country convention. In the following examples, South Africa (za) is used.

If you are translating a language and the country supports more than one official language, use the international language convention in lowercase with a minus separater (examples: cn-tw, cn-zh, cn-ct, en-us, en-uk, za-af, za-zu, za-eg).

This convention can be used to do special versions of a language (eg: 1337 speak = en-17, eh english = en-ee).


To start, first copy all the en (english) files to the folder of your language

for linux, in this folder (language):
cp -R en ./za

for windows, in this folder (language):
mkdir za
xcopy .\en\*.* .\za /E


In each file at the top, place your name (like I have in the english files).

If you any software for the translation, place that info at the top also (like I have in the norwegian files).

To test the language with eoCMS, you will need an SVN version 0.8.2 or later. See the bottom of the page if this file is not part of an eoCMS zip (you have language.zip)

You can install with english and when you use the various pages in eoCMS, you can add &lang=za to have the correct language text displayed.

When you have finished /language/za/dbdefaults.php and /language/za/installer.php you can install using the new language by adding ?lang=za (where za is the folder name):
 http://localhost/eocms/installer.php?lang=za


CREATOR.TXT:
Place the file (CREATOR.TXT) in the language folder you create.

In that place the OS and version you used, and the editor and its version also. These are in case there is a problem later on.

In CREATOR.TXT place the word for the installer language menu, see the Russian one as an example. I will need to add it to the menu if it is not already there.

You may also place any other information that you want, that you feel is relevant to the translation or the language, including any contact info if you would like to make it public.

ie CREATOR.TXT
OS = WindowsXP, Windows7, WindowsVista, Linux (state distro + version) OSX (state version)
editor = (state editor to be used + version)
menu = (replace with translated word)

eg CREATOR.TXT
OS = PCLinuxOS 2009.1
editor = kwrite
menu = Afrikaans

Further info:

Homepage and Support:
 http://eocms.com/
Dev Blog and SVN downloads:
 http://paulwratt.110mb.com/eocms/
