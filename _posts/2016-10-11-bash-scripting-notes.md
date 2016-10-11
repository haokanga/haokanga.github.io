---
layout: post
title:  "Linux-Bash Scripting Notes"
date:   2016-10-11 00:21:56 +1030
categories: Linux
tags: [Linux]
---
Notes during bash scripting.
<!--summary break-->


#### Check if file exists
{%highlight bash%}
if [ -f "$filename" ]
then
    # ...
fi
{%endhighlight%}

#### Check if folder exists
{%highlight bash%}
if [ -d /path/to/folder ]
then
    # ...
fi
{%endhighlight%}

#### String array for loop
{%highlight bash%}
declare -a arr=("file1" "file2" "file3")
for i in "${arr[@]}"
do
	if [ -f /path/to/"$i" ]
	then
		echo "$i already exists"
	fi
done
{%endhighlight%}

#### Set environment variables
{%highlight bash%}
# DO NOT USE export for environment variables
# `export` exports the variable assignment to sub-shells, i.e. shells which are started as child processes of the shell containing the export directive. Your command-line environment is the parent of the script's shell, so it does not see the variable assignment.

if ! grep -q "$sysenv" /etc/environment; then
	echo "$sysenv=$val" >> /etc/environment
fi
# Update environment variables
source /etc/environment
{%endhighlight%}