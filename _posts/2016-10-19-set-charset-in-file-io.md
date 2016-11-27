---
layout: post
title:  "Java-Set Charset in File I/O"
date:   2016-10-19 00:21:56 +1030
categories: Java
tags: [Java]
---
Definitive handy choices to set Charset in Java File IO.
<!--summary break-->

#### Append one line to a file (Naive solution)
{%highlight java%}
    /**
     * Append a line WITHOUT newline at the end
     *
     * @param line
     * @param filePath
     * @return true if successful
     */
    private boolean writeFile(String line, String filePath) {
        try {
            if (filePath == null || filePath.isEmpty()) {
                return false;
            } else {
                File file = new File(submissionFolder + filePath);
                if (!file.exists()) {
                    if (!file.createNewFile()) return false;
                }
                FileWriter fileWriter = new FileWriter(file, true);
                BufferedWriter bw = new BufferedWriter(fileWriter);
                bw.write(line);
                bw.close();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return false;
    }
{%endhighlight%}

It looks good, what's the problem here?

If you run [Findbugs](http://findbugs.sourceforge.net/) upon this snippet, you will get this SEVERE warning:

**Reliance on default encoding**

Found a call to a method which will perform a byte to String (or String to byte) conversion, and will assume that the default platform encoding is suitable. This will cause the application behaviour to vary between platforms. Use an alternative API and specify a charset name or Charset object explicitly.

This bug can be easily ignored when you only run and test your code in your own development environment, in which the default encoding will be set to UTF-8. However, if the production environment has another default encoding, your code will be troublesome.

Here is the rule you must keep in mind:
**ALWAYS set encoding in file I/O explicitly.**

Here are some examples which handles Charset well:

#### Examples with explicit Charset
{%highlight java%}
    BufferedReader br = new BufferedReader(
                    new InputStreamReader(new FileInputStream(inputFile), StandardCharsets.UTF_8));
    PrintWriter printWriter = new PrintWriter(outputFile, "UTF-8");
{%endhighlight%}

However, you might struggle to find ways to support both appending writing and charset setting in `java.io`.

#### Time for java.nio
Java NIO(New IO) has a straight-forward meaning that it is designed to be an alternative of `java.io`. You can use `java.nio.file.Path` for file path and `java.nio.file.Files` for file IO operations.

{%highlight java%}
    import java.nio.file.Files;
    import java.nio.file.Paths;

    import static java.nio.charset.StandardCharsets.UTF_8;
    import static java.nio.file.StandardOpenOption.APPEND;
    import static java.nio.file.StandardOpenOption.CREATE;

    /**
     * Append a line WITHOUT newline at the end
     *
     * @param line
     * @param filePath
     * @return true if successful
     */
    private boolean writeFile(String line, String filePath) {
        try {
            if (filePath == null || filePath.isEmpty()) {
                return false;
            } else {
                Files.write(Paths.get(filePath), line.getBytes(UTF_8), APPEND, CREATE);
                return true;
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return false;
    }
    
{%endhighlight%}


It is just so simple and graceful. Enjoy your life with Java NIO!
