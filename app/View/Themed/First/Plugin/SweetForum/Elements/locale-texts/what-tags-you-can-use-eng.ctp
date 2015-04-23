<h4>Formatting tags</h4>
<ul>
    <li><strong>Bold</strong> - &lt;strong&gt;Bold&lt;/strong&gt;</li>
    <li><em>Italic</em> - &lt;em&gt;Italic&lt;/em&gt;</li>
    <li><strike>Strike</strike> - &lt;strike&gt;Strike&lt;/strike&gt;</li>
    <li><a href='#'>Link</a> - &lt;a href="link url"&gt;Link&lt;/a&gt;</li>
</ul>

<hr>

<h4 class='margin-top15'>List tag</h4>
<p>For making lists you can use &lt;ul&gt; tag with &lt;li&gt;</p>
<p>For example:</p>
<p>
    &lt;ul&gt;<br/>
        &lt;li&gt;1&lt;/li&gt;<br/>
        &lt;li&gt;2&lt;/li&gt;<br/>
        &lt;li&gt;3&lt;/li&gt;<br/>
    &lt;/ul&gt;<br/>
</p>
<p>Will output like</p>
<ul>
    <li>1</li>
    <li>2</li>
    <li>3</li>
</ul>

<hr>

<h4 class='margin-top15'>Code formatting</h4>
<p>If you want to show some programming code, you can use &lt;code&gt; tag</p>
<p>For example:</p>
<p>
    &lt;code&gt;<br/>
    &nbsp;&nbsp;&lt;ul&gt;<br/>
        &nbsp;&nbsp;&nbsp;&lt;li&gt;1&lt;/li&gt;<br/>
        &nbsp;&nbsp;&nbsp;&lt;li&gt;2&lt;/li&gt;<br/>
        &nbsp;&nbsp;&nbsp;&lt;li&gt;3&lt;/li&gt;<br/>
    &nbsp;&nbsp;&lt;/ul&gt;<br/>
    &lt;/code&gt;
</p>
<p>Will output like</p>
<pre>
&lt;ul&gt;
    &lt;li&gt;1&lt;/li&gt;
    &lt;li&gt;2&lt;/li&gt;
    &lt;li&gt;3&lt;/li&gt;
&lt;/ul&gt;
</pre>

<hr>

<h4 class='margin-top15'>Media tags</h4>
<p><strong>Image</strong></p>
<p>To insert image use &lt;image&gt; tag</p>
<pre>
&lt;image&gt;https://i1.ytimg.com/vi/hN0JkFrvO_M/maxresdefault.jpg&lt;/image&gt;    
</pre>
<p>Result</p>
<img src='https://i1.ytimg.com/vi/hN0JkFrvO_M/maxresdefault.jpg' class='margin-bottom15' />
<p>Also, you can insert link from <strong>Instagram</strong>.</p>

<p><strong>Video</strong></p>
<p>
    To insert <strong>video</strong> use &lt;video&gt; tag.<br/>
    You can insert vidoe from <strong>YouTube</strong>, <strong>Vimeo</strong>, <strong>Vine</strong>, <strong>Instagram</strong>, <small>Coub.com</small>, <small>RuTube.ru</small><br/>
    Also, you can insert direct link to video (supports mp4, ogg, webm), it will be render like HTML5 video.
</p>
<pre>
&lt;video&gt;https://www.youtube.com/watch?v=hN0JkFrvO_M&lt;/video&gt;
&lt;video&gt;http://vimeo.com/94087291&lt;/video&gt;
&lt;video&gt;http://vine.co/v/bjHh0zHdgZT&lt;/video&gt;
&lt;video&gt;http://instagram.com/p/mGD8Wpt_MK/&lt;/video&gt;
&lt;video&gt;http://coub.com/view/x72z/&lt;/video&gt;
&lt;video&gt;http://somedomain.com/somevideo.mp4&lt;/video&gt;
</pre>

<p><strong>Audio</strong></p>
<p>
    To insert <strong>audio</strong> files use &lt;audio&gt; tag.<br/>
    You can insert URL from <strong>Soundcould</strong> or direct link to audio file (supports mp3, ogg, wav), it will be render like HTML5 audio.
</p>
<pre>
&lt;audio&gt;https://soundcloud.com/nero/satisfy&lt;/video&gt;
</pre>

<hr>
<h4 class='margin-top15'>Other tags</h4>
<p><strong>&lt;social-quote&gt;&lt;/social-quote&gt;</strong></p>
<p>You can insert post from Twitter using this tag (now supports only Twitter).</p>
<pre>
&lt;social-quote&gt;<br/>https://twitter.com/NatGeo/status/468954349952331776<br/>&lt;/social-quote&gt;
</pre>