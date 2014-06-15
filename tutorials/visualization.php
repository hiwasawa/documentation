<!DOCTYPE html>
<html lang="en">
<head>
<title>Documentation - Point Cloud Library (PCL)</title>
</head>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>PCL Visualization overview</title>
    
    <link rel="stylesheet" href="_static/sphinxdoc.css" type="text/css" />
    <link rel="stylesheet" href="_static/pygments.css" type="text/css" />
    
    <script type="text/javascript">
      var DOCUMENTATION_OPTIONS = {
        URL_ROOT:    './',
        VERSION:     '0.0',
        COLLAPSE_INDEX: false,
        FILE_SUFFIX: '.php',
        HAS_SOURCE:  true
      };
    </script>
    <script type="text/javascript" src="_static/jquery.js"></script>
    <script type="text/javascript" src="_static/underscore.js"></script>
    <script type="text/javascript" src="_static/doctools.js"></script>
    <link rel="top" title="None" href="index.php" />
<?php
define('MODX_CORE_PATH', '/var/www/pointclouds.org/core/');
define('MODX_CONFIG_KEY', 'config');

require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');

$snip = $modx->runSnippet("getSiteNavigation", array('id'=>5, 'phLevels'=>'sitenav.level0,sitenav.level1', 'showPageNav'=>'n'));
$chunkOutput = $modx->getChunk("site-header", array('sitenav'=>$snip));
$bodytag = str_replace("[[+showSubmenus:notempty=`", "", $chunkOutput);
$bodytag = str_replace("`]]", "", $bodytag);
echo $bodytag;
echo "\n";
?>
<div id="pagetitle">
<h1>Documentation</h1>
<a id="donate" href="http://www.openperception.org/support/"><img src="/assets/images/donate-button.png" alt="Donate to the Open Perception foundation"/></a>
</div>
<div id="page-content">

  </head>
  <body>

    <div class="document">
      <div class="documentwrapper">
          <div class="body">
            
  <div class="section" id="pcl-visualization-overview">
<span id="visualization"></span><h1>PCL Visualization overview</h1>
<p>The <strong>pcl_visualization</strong> library was built for the purpose of being able to
quickly prototype and visualize the results of algorithms operating on 3D point
cloud data. Similar to OpenCV&#8217;s <strong>highgui</strong> routines for displaying 2D images
and for drawing basic 2D shapes on screen, the library offers:</p>
<blockquote>
<div><ul class="simple">
<li>methods for rendering and setting visual properties (colors, point sizes,
opacity, etc) for any n-D point cloud datasets in pcl::PointCloud&lt;T&gt; format;</li>
</ul>
<blockquote>
<div><img alt="_images/bunny.jpg" src="_images/bunny.jpg" />
</div></blockquote>
<ul class="simple">
<li>methods for drawing basic 3D shapes on screen (e.g., cylinders, spheres,
lines, polygons, etc) either from sets of points or from parametric
equations;</li>
</ul>
<blockquote>
<div><img alt="_images/shapes.jpg" src="_images/shapes.jpg" />
</div></blockquote>
<ul class="simple">
<li>a histogram visualization module (PCLHistogramVisualizer) for 2D plots;</li>
</ul>
<blockquote>
<div><img alt="_images/histogram.jpg" src="_images/histogram.jpg" />
</div></blockquote>
<ul class="simple">
<li>a multitude of Geometry and Color handler for pcl::PointCloud&lt;T&gt; datasets;</li>
</ul>
<blockquote>
<div><img alt="_images/normals.jpg" src="_images/normals.jpg" />
<img alt="_images/pcs.jpg" src="_images/pcs.jpg" />
</div></blockquote>
<ul class="simple">
<li>a pcl::RangeImage visualization module.</li>
</ul>
<blockquote>
<div><img alt="_images/range_image.jpg" src="_images/range_image.jpg" />
</div></blockquote>
</div></blockquote>
<p>The package makes use of the VTK library for 3D rendering for
range image and 2D operations.</p>
<p>For implementing your own visualizers, take a look at the tests and examples
accompanying the library.</p>
<div class="admonition note">
<p class="first admonition-title">Note</p>
<p class="last">Due to historical reasons, PCL 1.x stores RGB data as a packed float (to
preserve backward compatibility). To learn more about this, please see the
<a class="reference external" href="http://docs.pointclouds.org/trunk/structpcl_1_1_point_x_y_z_r_g_b.html">PointXYZRGB</a>.</p>
</div>
</div>
<div class="section" id="simple-cloud-visualization">
<h1>Simple Cloud Visualization</h1>
<p>If you just want to visualize something in your app with a few lines of code,
use a snippet like the following one:</p>
<div class="highlight-cpp"><table class="highlighttable"><tr><td class="linenos"><div class="linenodiv"><pre> 1
 2
 3
 4
 5
 6
 7
 8
 9
10
11
12
13</pre></div></td><td class="code"><div class="highlight"><pre> <span class="cp">#include &lt;pcl_visualization/cloud_viewer.h&gt;</span>
 <span class="c1">//...</span>
 <span class="kt">void</span>
 <span class="nf">foo</span> <span class="p">()</span>
 <span class="p">{</span>
   <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZRGB</span><span class="o">&gt;</span> <span class="n">cloud</span><span class="p">;</span>
   <span class="c1">//... populate cloud</span>
   <span class="n">pcl_visualization</span><span class="o">::</span><span class="n">CloudViewer</span> <span class="n">viewer</span><span class="p">(</span><span class="s">&quot;Simple Cloud Viewer&quot;</span><span class="p">);</span>
   <span class="n">viewer</span><span class="p">.</span><span class="n">showCloud</span><span class="p">(</span><span class="n">cloud</span><span class="p">);</span>
   <span class="k">while</span> <span class="p">(</span><span class="o">!</span><span class="n">viewer</span><span class="p">.</span><span class="n">wasStopped</span><span class="p">())</span>
   <span class="p">{</span>
   <span class="p">}</span>
 <span class="p">}</span>
</pre></div>
</td></tr></table></div>
</div>
<div class="section" id="pcd-viewer">
<h1>PCD Viewer</h1>
<p>A quick way for visualizing PCD (Point Cloud Data) files is by using
<strong>pcl_viewer</strong>. As of 0.2.7, pcl_viewer&#8217;s help screen looks like:</p>
<div class="highlight-python"><div class="highlight"><pre>Syntax is: pcl_viewer &lt;file_name 1..N&gt;.pcd &lt;options&gt;
  where options are:
                     -bc r,g,b                = background color
                     -fc r,g,b                = foreground color
                     -ps X                    = point size (1..64)
                     -opaque X                = rendered point cloud opacity (0..1)
                     -ax n                    = enable on-screen display of XYZ axes and scale them to n
                     -ax_pos X,Y,Z            = if axes are enabled, set their X,Y,Z position in space (default 0,0,0)

                     -cam (*)                 = use given camera settings as initial view
 (*) [Clipping Range / Focal Point / Position / ViewUp / Distance / Window Size / Window Pos] or use a &lt;filename.cam&gt; that contains the same information.

                     -multiview 0/1           = enable/disable auto-multi viewport rendering (default disabled)


                     -normals 0/X             = disable/enable the display of every Xth point&#39;s surface normal as lines (default disabled)
                     -normals_scale X         = resize the normal unit vector size to X (default 0.02)

                     -pc 0/X                  = disable/enable the display of every Xth point&#39;s principal curvatures as lines (default disabled)
                     -pc_scale X              = resize the principal curvatures vectors size to X (default 0.02)


(Note: for multiple .pcd files, provide multiple -{fc,ps} parameters; they will be automatically assigned to the right file)
</pre></div>
</div>
</div>
<div class="section" id="usage-examples">
<h1>Usage examples</h1>
<div class="highlight-bash"><div class="highlight"><pre><span class="nv">$ </span>pcl_viewer -multiview <span class="m">1</span> data/partial_cup_model.pcd data/partial_cup_model.pcd data/partial_cup_model.pcd
</pre></div>
</div>
<p>The above will load the <tt class="docutils literal"><span class="pre">partial_cup_model.pcd</span></tt> file 3 times, and will create a
multi-viewport rendering (<tt class="docutils literal"><span class="pre">-multiview</span> <span class="pre">1</span></tt>).</p>
<img alt="_images/ex1.jpg" src="_images/ex1.jpg" />
<p>Pressing <tt class="docutils literal"><span class="pre">h</span></tt> while the point clouds are being rendered will output the
following information on the console:</p>
<div class="highlight-python"><div class="highlight"><pre>| Help:
-------
          p, P   : switch to a point-based representation
          w, W   : switch to a wireframe-based representation (where available)
          s, S   : switch to a surface-based representation (where available)

          j, J   : take a .PNG snapshot of the current window view
          c, C   : display current camera/window parameters

         + / -   : increment/decrement overall point size

          g, G   : display scale grid (on/off)
          u, U   : display lookup table (on/off)

    r, R [+ ALT] : reset camera [to viewpoint = {0, 0, 0} -&gt; center_{x, y, z}]

    ALT + s, S   : turn stereo mode on/off
    ALT + f, F   : switch between maximized window mode and original size

          l, L           : list all available geometric and color handlers for the current actor map
    ALT + 0..9 [+ CTRL]  : switch between different geometric handlers (where available)
          0..9 [+ CTRL]  : switch between different color handlers (where available)
</pre></div>
</div>
<p>Pressing <tt class="docutils literal"><span class="pre">l</span></tt> will show the current list of available geometry/color handlers
for the datasets that we loaded. In this example:</p>
<div class="highlight-python"><div class="highlight"><pre>List of available geometry handlers for actor partial_cup_model.pcd-0: xyz(1) normal_xyz(2)
List of available color handlers for actor partial_cup_model.pcd-0: [random](1) x(2) y(3) z(4) normal_x(5) normal_y(6) normal_z(7) curvature(8) boundary(9) k(10) principal_curvature_x(11) principal_curvature_y(12) principal_curvature_z(13) pc1(14) pc2(15)
</pre></div>
</div>
<p>Switching to a <tt class="docutils literal"><span class="pre">normal_xyz</span></tt> geometric handler using <tt class="docutils literal"><span class="pre">ALT+1</span></tt> and then
pressing <tt class="docutils literal"><span class="pre">8</span></tt> to switch to a curvature color handler, should result in the
following:</p>
<div class="highlight-python"><div class="highlight"><pre>$ pcl_viewer -normals 100 data/partial_cup_model.pcd
</pre></div>
</div>
<img alt="_images/ex2.jpg" src="_images/ex2.jpg" />
<p>The above will load the <tt class="docutils literal"><span class="pre">partial_cup_model.pcd</span></tt> file and render its every
<tt class="docutils literal"><span class="pre">100</span></tt> th surface normal on screen.</p>
<div class="highlight-bash"><div class="highlight"><pre><span class="nv">$ </span>pcl_viewer -pc <span class="m">100</span> data/partial_cup_model.pcd
</pre></div>
</div>
<img alt="_images/ex3.jpg" src="_images/ex3.jpg" />
<p>The above will load the <tt class="docutils literal"><span class="pre">partial_cup_model.pcd</span></tt> file and render its every
<tt class="docutils literal"><span class="pre">100</span></tt> th principal curvature (+surface normal) on screen.</p>
<img alt="_images/ex4.jpg" src="_images/ex4.jpg" />
<div class="highlight-bash"><div class="highlight"><pre><span class="nv">$ </span>pcl_viewer data/bun000.pcd data/bun045.pcd -ax 0.5 -ps <span class="m">3</span> -ps 1
</pre></div>
</div>
<p>The above assumes that the <tt class="docutils literal"><span class="pre">bun000.pcd</span></tt> and <tt class="docutils literal"><span class="pre">bun045.pcd</span></tt> datasets have been
downloaded and are available. The results shown in the following picture were
obtained after pressing <tt class="docutils literal"><span class="pre">u</span></tt> and <tt class="docutils literal"><span class="pre">g</span></tt> to enable the lookup table and on-grid
display.</p>
<img alt="_images/ex5.jpg" src="_images/ex5.jpg" />
</div>
<div class="section" id="range-image-visualizer">
<h1>Range Image Visualizer</h1>
<p>A quick way for visualizing range images is by using the binary of the tutorial
for range_image_visualization:</p>
<div class="highlight-python"><div class="highlight"><pre>$ tutorial_range_image_visualization data/office_scene.pcd
</pre></div>
</div>
<p>The above will load the <tt class="docutils literal"><span class="pre">office_scene.pcd</span></tt> point cloud file, create a range
image from it and visualize both, the point cloud and the range image.</p>
</div>


          </div>
      </div>
      <div class="clearer"></div>
    </div>
</div> <!-- #page-content -->

<?php
$chunkOutput = $modx->getChunk("site-footer");
echo $chunkOutput;
?>

  </body>
</html>