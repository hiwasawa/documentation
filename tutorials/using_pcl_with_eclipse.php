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
    
    <title>Using PCL with Eclipse</title>
    
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
            
  <div class="section" id="using-pcl-with-eclipse">
<span id="id1"></span><h1><a class="toc-backref" href="#id2">Using PCL with Eclipse</a></h1>
<p>This tutorial explains how to use Eclipse as an IDE to manage your PCL projects. It was tested under Ubuntu 14.04 with Eclipse Luna;
do not hesitate to modify this tutorial by submitting a pull request on GitHub to add other configurations etc.</p>
<div class="contents topic" id="contents">
<p class="topic-title first">Contents</p>
<ul class="simple">
<li><a class="reference internal" href="#using-pcl-with-eclipse" id="id2">Using PCL with Eclipse</a><ul>
<li><a class="reference internal" href="#prerequisites" id="id3">Prerequisites</a></li>
<li><a class="reference internal" href="#creating-the-eclipse-project-files" id="id4">Creating the eclipse project files</a></li>
<li><a class="reference internal" href="#importing-into-eclipse" id="id5">Importing into Eclipse</a><ul>
<li><a class="reference internal" href="#configuring-eclipse" id="id6">Configuring Eclipse</a></li>
</ul>
</li>
<li><a class="reference internal" href="#setting-the-pcl-code-style-in-eclipse" id="id7">Setting the PCL code style in Eclipse</a><ul>
<li><a class="reference internal" href="#global" id="id8">Global</a></li>
<li><a class="reference internal" href="#project-specific" id="id9">Project specific</a></li>
<li><a class="reference internal" href="#how-to-format-the-code" id="id10">How to format the code</a></li>
</ul>
</li>
<li><a class="reference internal" href="#launching-the-program" id="id11">Launching the program</a></li>
<li><a class="reference internal" href="#where-to-get-more-information" id="id12">Where to get more information</a></li>
</ul>
</li>
</ul>
</div>
<div class="section" id="prerequisites">
<h2><a class="toc-backref" href="#id3">Prerequisites</a></h2>
<p>We assume you have downloaded and extracted a PCL version (either PCL trunk or a stable version) on your machine.
For the example, we will use the <a class="reference external" href="http://www.pointclouds.org/documentation/tutorials/pcl_visualizer.php">pcl visualizer</a> code.</p>
</div>
<div class="section" id="creating-the-eclipse-project-files">
<h2><a class="toc-backref" href="#id4">Creating the eclipse project files</a></h2>
<p>The files are organized like the following tree:</p>
<div class="highlight-python"><div class="highlight"><pre>.
├── build
└── src
    ├── CMakeLists.txt
    └── pcl_visualizer_demo.cpp
</pre></div>
</div>
<p>Open a terminal, navigate to your project root folder and configure the project:</p>
<div class="highlight-python"><div class="highlight"><pre>$ cd /path_to_my_project/build
$ cmake -G &quot;Eclipse CDT4 - Unix Makefiles&quot; ../src
</pre></div>
</div>
<p>You will see something that should look like:</p>
<div class="highlight-python"><div class="highlight"><pre>-- The C compiler identification is GNU 4.8.2
-- The CXX compiler identification is GNU 4.8.2
-- Could not determine Eclipse version, assuming at least 3.6 (Helios). Adjust CMAKE_ECLIPSE_VERSION if this is wrong.
-- Check for working C compiler: /usr/lib/ccache/cc
-- Check for working C compiler: /usr/lib/ccache/cc   -- works
-- Detecting C compiler ABI info
-- Detecting C compiler ABI info - done
-- Check for working CXX compiler: /usr/lib/ccache/c++
-- Check for working CXX compiler: /usr/lib/ccache/c++   -- works
-- Detecting CXX compiler ABI info
-- Detecting CXX compiler ABI info - done
-- checking for module &#39;eigen3&#39;
--   found eigen3, version 3.2.0
-- Found eigen: /usr/include/eigen3
-- Boost version: 1.54.0
-- Found the following Boost libraries:
--   system
--   filesystem
--   thread
--   date_time
--   iostreams
--   mpi
--   serialization
--   chrono
-- checking for module &#39;openni-dev&#39;
--   package &#39;openni-dev&#39; not found
-- Found openni: /usr/lib/libOpenNI.so
-- checking for module &#39;openni2-dev&#39;
--   package &#39;openni2-dev&#39; not found
-- Found OpenNI2: /usr/lib/libOpenNI2.so
** WARNING ** io features related to pcap will be disabled
** WARNING ** io features related to png will be disabled
-- Found libusb-1.0: /usr/include
-- checking for module &#39;flann&#39;
--   found flann, version 1.8.4
-- Found Flann: /usr/lib/x86_64-linux-gnu/libflann_cpp_s.a
-- Found qhull: /usr/lib/x86_64-linux-gnu/libqhull.so
-- checking for module &#39;openni-dev&#39;
--   package &#39;openni-dev&#39; not found
-- checking for module &#39;openni2-dev&#39;
--   package &#39;openni2-dev&#39; not found
-- looking for PCL_COMMON
-- Found PCL_COMMON: /usr/local/lib/libpcl_common.so
-- looking for PCL_OCTREE
-- Found PCL_OCTREE: /usr/local/lib/libpcl_octree.so
-- looking for PCL_IO
-- Found PCL_IO: /usr/local/lib/libpcl_io.so
-- looking for PCL_KDTREE
-- Found PCL_KDTREE: /usr/local/lib/libpcl_kdtree.so
-- looking for PCL_SEARCH
-- Found PCL_SEARCH: /usr/local/lib/libpcl_search.so
-- looking for PCL_SAMPLE_CONSENSUS
-- Found PCL_SAMPLE_CONSENSUS: /usr/local/lib/libpcl_sample_consensus.so
-- looking for PCL_FILTERS
-- Found PCL_FILTERS: /usr/local/lib/libpcl_filters.so
-- looking for PCL_2D
-- Found PCL_2D: /usr/local/include/pcl-1.7
-- looking for PCL_FEATURES
-- Found PCL_FEATURES: /usr/local/lib/libpcl_features.so
-- looking for PCL_GEOMETRY
-- Found PCL_GEOMETRY: /usr/local/include/pcl-1.7
-- looking for PCL_KEYPOINTS
-- Found PCL_KEYPOINTS: /usr/local/lib/libpcl_keypoints.so
-- looking for PCL_SURFACE
-- Found PCL_SURFACE: /usr/local/lib/libpcl_surface.so
-- looking for PCL_REGISTRATION
-- Found PCL_REGISTRATION: /usr/local/lib/libpcl_registration.so
-- looking for PCL_ML
-- Found PCL_ML: /usr/local/lib/libpcl_ml.so
-- looking for PCL_SEGMENTATION
-- Found PCL_SEGMENTATION: /usr/local/lib/libpcl_segmentation.so
-- looking for PCL_RECOGNITION
-- Found PCL_RECOGNITION: /usr/local/lib/libpcl_recognition.so
-- looking for PCL_VISUALIZATION
-- Found PCL_VISUALIZATION: /usr/local/lib/libpcl_visualization.so
-- looking for PCL_PEOPLE
-- Found PCL_PEOPLE: /usr/local/lib/libpcl_people.so
-- looking for PCL_OUTOFCORE
-- Found PCL_OUTOFCORE: /usr/local/lib/libpcl_outofcore.so
-- looking for PCL_TRACKING
-- Found PCL_TRACKING: /usr/local/lib/libpcl_tracking.so
-- looking for PCL_STEREO
-- Found PCL_STEREO: /usr/local/lib/libpcl_stereo.so
-- looking for PCL_GPU_CONTAINERS
-- Found PCL_GPU_CONTAINERS: /usr/local/lib/libpcl_gpu_containers.so
-- looking for PCL_GPU_UTILS
-- Found PCL_GPU_UTILS: /usr/local/lib/libpcl_gpu_utils.so
-- looking for PCL_GPU_OCTREE
-- Found PCL_GPU_OCTREE: /usr/local/lib/libpcl_gpu_octree.so
-- looking for PCL_GPU_FEATURES
-- Found PCL_GPU_FEATURES: /usr/local/lib/libpcl_gpu_features.so
-- looking for PCL_GPU_KINFU
-- Found PCL_GPU_KINFU: /usr/local/lib/libpcl_gpu_kinfu.so
-- looking for PCL_GPU_KINFU_LARGE_SCALE
-- Found PCL_GPU_KINFU_LARGE_SCALE: /usr/local/lib/libpcl_gpu_kinfu_large_scale.so
-- looking for PCL_GPU_SEGMENTATION
-- Found PCL_GPU_SEGMENTATION: /usr/local/lib/libpcl_gpu_segmentation.so
-- looking for PCL_CUDA_COMMON
-- Found PCL_CUDA_COMMON: /usr/local/include/pcl-1.7
-- looking for PCL_CUDA_FEATURES
-- Found PCL_CUDA_FEATURES: /usr/local/lib/libpcl_cuda_features.so
-- looking for PCL_CUDA_SEGMENTATION
-- Found PCL_CUDA_SEGMENTATION: /usr/local/lib/libpcl_cuda_segmentation.so
-- looking for PCL_CUDA_SAMPLE_CONSENSUS
-- Found PCL_CUDA_SAMPLE_CONSENSUS: /usr/local/lib/libpcl_cuda_sample_consensus.so
-- Found PCL: /usr/lib/x86_64-linux-gnu/libboost_system.so;/usr/lib/x86_64-linux-gnu/libboost_filesystem.so;/usr/lib/x86_64-linux-gnu/libboost_thread.so;/usr/lib/x86_64-linux-gnu/libboost_date_time.so;/usr/lib/x86_64-linux-gnu/libboost_iostreams.so;/usr/lib/x86_64-linux-gnu/libboost_mpi.so;/usr/lib/x86_64-linux-gnu/libboost_serialization.so;/usr/lib/x86_64-linux-gnu/libboost_chrono.so;/usr/lib/x86_64-linux-gnu/libpthread.so;optimized;/usr/local/lib/libpcl_common.so;debug;/usr/local/lib/libpcl_common.so;optimized;/usr/local/lib/libpcl_octree.so;debug;/usr/local/lib/libpcl_octree.so;/usr/lib/libOpenNI.so;/usr/lib/libOpenNI2.so;vtkCommon;vtkFiltering;vtkImaging;vtkGraphics;vtkGenericFiltering;vtkIO;vtkRendering;vtkVolumeRendering;vtkHybrid;vtkWidgets;vtkParallel;vtkInfovis;vtkGeovis;vtkViews;vtkCharts;optimized;/usr/local/lib/libpcl_io.so;debug;/usr/local/lib/libpcl_io.so;optimized;/usr/lib/x86_64-linux-gnu/libflann_cpp_s.a;debug;/usr/lib/x86_64-linux-gnu/libflann_cpp_s.a;optimized;/usr/local/lib/libpcl_kdtree.so;debug;/usr/local/lib/libpcl_kdtree.so;optimized;/usr/local/lib/libpcl_search.so;debug;/usr/local/lib/libpcl_search.so;optimized;/usr/local/lib/libpcl_sample_consensus.so;debug;/usr/local/lib/libpcl_sample_consensus.so;optimized;/usr/local/lib/libpcl_filters.so;debug;/usr/local/lib/libpcl_filters.so;optimized;/usr/local/lib/libpcl_features.so;debug;/usr/local/lib/libpcl_features.so;optimized;/usr/local/lib/libpcl_keypoints.so;debug;/usr/local/lib/libpcl_keypoints.so;optimized;/usr/lib/x86_64-linux-gnu/libqhull.so;debug;/usr/lib/x86_64-linux-gnu/libqhull.so;optimized;/usr/local/lib/libpcl_surface.so;debug;/usr/local/lib/libpcl_surface.so;optimized;/usr/local/lib/libpcl_registration.so;debug;/usr/local/lib/libpcl_registration.so;optimized;/usr/local/lib/libpcl_ml.so;debug;/usr/local/lib/libpcl_ml.so;optimized;/usr/local/lib/libpcl_segmentation.so;debug;/usr/local/lib/libpcl_segmentation.so;optimized;/usr/local/lib/libpcl_recognition.so;debug;/usr/local/lib/libpcl_recognition.so;optimized;/usr/local/lib/libpcl_visualization.so;debug;/usr/local/lib/libpcl_visualization.so;optimized;/usr/local/lib/libpcl_people.so;debug;/usr/local/lib/libpcl_people.so;optimized;/usr/local/lib/libpcl_outofcore.so;debug;/usr/local/lib/libpcl_outofcore.so;optimized;/usr/local/lib/libpcl_tracking.so;debug;/usr/local/lib/libpcl_tracking.so;optimized;/usr/local/lib/libpcl_stereo.so;debug;/usr/local/lib/libpcl_stereo.so;optimized;/usr/local/lib/libpcl_gpu_containers.so;debug;/usr/local/lib/libpcl_gpu_containers.so;optimized;/usr/local/lib/libpcl_gpu_utils.so;debug;/usr/local/lib/libpcl_gpu_utils.so;optimized;/usr/local/lib/libpcl_gpu_octree.so;debug;/usr/local/lib/libpcl_gpu_octree.so;optimized;/usr/local/lib/libpcl_gpu_features.so;debug;/usr/local/lib/libpcl_gpu_features.so;optimized;/usr/local/lib/libpcl_gpu_kinfu.so;debug;/usr/local/lib/libpcl_gpu_kinfu.so;optimized;/usr/local/lib/libpcl_gpu_kinfu_large_scale.so;debug;/usr/local/lib/libpcl_gpu_kinfu_large_scale.so;optimized;/usr/local/lib/libpcl_gpu_segmentation.so;debug;/usr/local/lib/libpcl_gpu_segmentation.so;optimized;/usr/local/lib/libpcl_cuda_features.so;debug;/usr/local/lib/libpcl_cuda_features.so;optimized;/usr/local/lib/libpcl_cuda_segmentation.so;debug;/usr/local/lib/libpcl_cuda_segmentation.so;optimized;/usr/local/lib/libpcl_cuda_sample_consensus.so;debug;/usr/local/lib/libpcl_cuda_sample_consensus.so;/usr/lib/x86_64-linux-gnu/libboost_system.so;/usr/lib/x86_64-linux-gnu/libboost_filesystem.so;/usr/lib/x86_64-linux-gnu/libboost_thread.so;/usr/lib/x86_64-linux-gnu/libboost_date_time.so;/usr/lib/x86_64-linux-gnu/libboost_iostreams.so;/usr/lib/x86_64-linux-gnu/libboost_mpi.so;/usr/lib/x86_64-linux-gnu/libboost_serialization.so;/usr/lib/x86_64-linux-gnu/libboost_chrono.so;/usr/lib/x86_64-linux-gnu/libpthread.so;optimized;/usr/lib/x86_64-linux-gnu/libqhull.so;debug;/usr/lib/x86_64-linux-gnu/libqhull.so;/usr/lib/libOpenNI.so;/usr/lib/libOpenNI2.so;optimized;/usr/lib/x86_64-linux-gnu/libflann_cpp_s.a;debug;/usr/lib/x86_64-linux-gnu/libflann_cpp_s.a;vtkCommon;vtkFiltering;vtkImaging;vtkGraphics;vtkGenericFiltering;vtkIO;vtkRendering;vtkVolumeRendering;vtkHybrid;vtkWidgets;vtkParallel;vtkInfovis;vtkGeovis;vtkViews;vtkCharts (Required is at least version &quot;1.7&quot;)
-- Configuring done
-- Generating done
-- Build files have been written to: /home/dell/visualizer/build
</pre></div>
</div>
</div>
<div class="section" id="importing-into-eclipse">
<h2><a class="toc-backref" href="#id5">Importing into Eclipse</a></h2>
<ul class="simple">
<li>Launch <a class="reference external" href="http://eclipse.org/cdt/">Eclipse CDT</a> and select <tt class="docutils literal"><span class="pre">File</span> <span class="pre">&gt;</span> <span class="pre">Import</span></tt>.</li>
<li>In the list select  <tt class="docutils literal"><span class="pre">General</span> <span class="pre">&gt;</span> <span class="pre">Existing</span> <span class="pre">Projects</span> <span class="pre">into</span> <span class="pre">Workspace</span></tt> and then next.</li>
<li>Browse (<tt class="docutils literal"><span class="pre">Select</span> <span class="pre">root</span> <span class="pre">directory</span></tt>) to the root folder of the project and select the <tt class="docutils literal"><span class="pre">build</span></tt> folder (in the example case, <tt class="docutils literal"><span class="pre">/home/dell/visualizer/build</span></tt>).</li>
<li>Click <tt class="docutils literal"><span class="pre">Finish</span></tt>.</li>
</ul>
<div class="admonition warning">
<p class="first admonition-title">Warning</p>
<p class="last">The Eclipse indexer is going to parse the files in the project (and all the includes), this can take a lot of time and might crash Eclipse if it&#8217;s not configured for big projects.
Take a look at the bottom right of Eclipse&#8217;s window to see the indexer status; it is advised not to do anything until the indexer has finished it&#8217;s job.</p>
</div>
<div class="section" id="configuring-eclipse">
<h3><a class="toc-backref" href="#id6">Configuring Eclipse</a></h3>
<p>If Eclipse fails to open your PCL project you might need to change Eclipse configuration; here are some values that should solve all problems
(but might not work on light hardware configurations):</p>
<div class="highlight-python"><div class="highlight"><pre>$ sudo gedit /usr/lib/eclipse/eclipse.ini
</pre></div>
</div>
<p>Change the values in the last lines:</p>
<div class="highlight-python"><div class="highlight"><pre>org.eclipse.platform
--launcher.XXMaxPermSize
1024m
--launcher.defaultAction
openFile
--launcher.appendVmargs
-vmargs
-Dosgi.requiredJavaVersion=1.7
-XX:MaxPermSize=512m
-Xms1024m
-Xmx1024m
</pre></div>
</div>
<p>Restart Eclipse and go to <tt class="docutils literal"><span class="pre">Windows</span> <span class="pre">&gt;</span> <span class="pre">Preferences</span></tt>, then <tt class="docutils literal"><span class="pre">C/C++</span> <span class="pre">&gt;</span> <span class="pre">Indexer</span> <span class="pre">&gt;</span> <span class="pre">Cache</span> <span class="pre">Limits</span></tt>. Set the limits to [50% | 512 | 512].</p>
</div>
</div>
<div class="section" id="setting-the-pcl-code-style-in-eclipse">
<h2><a class="toc-backref" href="#id7">Setting the PCL code style in Eclipse</a></h2>
<p>You can find a PCL code style file for Eclipse in <a class="reference external" href="https://github.com/PointCloudLibrary/pcl/blob/master/doc/advanced/content/files/PCL_eclipse_profile.xml">PCL GitHub trunk</a></p>
<div class="section" id="global">
<h3><a class="toc-backref" href="#id8">Global</a></h3>
<p>If you want to apply the PCL style guide to all projects:
<tt class="docutils literal"><span class="pre">Windows</span> <span class="pre">&gt;</span> <span class="pre">Preferences</span> <span class="pre">&gt;</span> <span class="pre">C/C++</span> <span class="pre">&gt;</span> <span class="pre">Code</span> <span class="pre">Style</span> <span class="pre">&gt;</span> <span class="pre">Formatter</span></tt></p>
</div>
<div class="section" id="project-specific">
<h3><a class="toc-backref" href="#id9">Project specific</a></h3>
<p>If you want to apply the style guide only to one project:
Go to <tt class="docutils literal"><span class="pre">Project</span> <span class="pre">&gt;</span> <span class="pre">Properties</span></tt>, then select <tt class="docutils literal"><span class="pre">Code</span> <span class="pre">Style</span></tt> in the left field and Enable <tt class="docutils literal"><span class="pre">project</span> <span class="pre">specific</span> <span class="pre">settings</span></tt>, then <tt class="docutils literal"><span class="pre">Import</span></tt> and select where you profile file (.xml) is.</p>
</div>
<div class="section" id="how-to-format-the-code">
<h3><a class="toc-backref" href="#id10">How to format the code</a></h3>
<p>If you want to format the whole project use <tt class="docutils literal"><span class="pre">Source</span> <span class="pre">&gt;</span> <span class="pre">Format</span></tt>. If you want to format only your selection use the shortcut <tt class="docutils literal"><span class="pre">Ctrl</span> <span class="pre">+</span> <span class="pre">Shift</span> <span class="pre">+</span> <span class="pre">F</span></tt></p>
</div>
</div>
<div class="section" id="launching-the-program">
<h2><a class="toc-backref" href="#id11">Launching the program</a></h2>
<p>To build the project, click on the build icon</p>
<a class="reference internal image-reference" href="_images/build_tab.gif"><img alt="_images/build_tab.gif" src="_images/build_tab.gif" style="height: 16px;" /></a>
<ul class="simple">
<li>Create a launch configuration, select the project on the left panel (left click on the project name); <tt class="docutils literal"><span class="pre">Run</span> <span class="pre">&gt;</span> <span class="pre">Run</span> <span class="pre">Configurations..</span></tt>.</li>
<li>Create a new <tt class="docutils literal"><span class="pre">C/C++</span> <span class="pre">Application</span></tt> click on <tt class="docutils literal"><span class="pre">Search</span> <span class="pre">Project</span></tt> and choose the executable to be launched.</li>
<li>Go the second tab (<tt class="docutils literal"><span class="pre">Arguments</span></tt>) and enter your arguments; remember this is not a terminal and <tt class="docutils literal"><span class="pre">~</span></tt> won&#8217;t work to get to your home folder for example !</li>
</ul>
<p>Run the program by clicking on the run icon</p>
<a class="reference internal image-reference" href="_images/lrun_obj.gif"><img alt="_images/lrun_obj.gif" src="_images/lrun_obj.gif" style="height: 16px;" /></a>
<p>The Eclipse console doesn&#8217;t manage ANSI colours, you could use <a class="reference external" href="http://www.mihai-nita.net/eclipse/">an ANSI console plugin</a> to get rid of the &#8220;[0m&#8221; characters in the output.</p>
</div>
<div class="section" id="where-to-get-more-information">
<h2><a class="toc-backref" href="#id12">Where to get more information</a></h2>
<p>You can get more information about the Eclipse CDT4 Generator <a class="reference external" href="http://www.vtk.org/Wiki/Eclipse_CDT4_Generator">here</a>.</p>
</div>
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