---
layout: post
title: "Regex-Regular Expressions, the Controversial Magic"
date: 2017-7-28 10:50:56 -0500
categories: Regex
tags: [Regex]
---
> The essence of any controversy is always about the usage of a tool, not the tool itself.
<!--summary break-->

As discussed in [one of the most epic answers](https://stackoverflow.com/questions/1732348/regex-match-open-tags-except-xhtml-self-contained-tags) in StackOverflow:

You can't parse [X]HTML with regex. Because HTML can't be parsed by regex. Regex is not a tool that can be used to correctly parse HTML.

HTML is not a regular language and hence cannot be parsed by regular expressions.

Don't use regular expressions when there are parsers. More generally, when you have better tools to do your job.

