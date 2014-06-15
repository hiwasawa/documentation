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
    
    <title>Region growing segmentation</title>
    
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
            
  <div class="section" id="region-growing-segmentation">
<span id="id1"></span><h1>Region growing segmentation</h1>
<p>In this tutorial we will learn how to use the region growing algorithm implemented in the <tt class="docutils literal"><span class="pre">pcl::RegionGrowing</span></tt> class.
The purpose of the said algorithm is to merge the points that are close enough in terms of the smoothness constraint.
Thereby, the output of this algorithm is the set of clusters,
were each cluster is a set of points that are considered to be a part of the same smooth surface.
The work of this algorithm is based on the comparison of the angles between the points normals.</p>
</div>
<div class="section" id="theoretical-primer">
<h1>Theoretical Primer</h1>
<p>Let&#8217;s take a look on how the algorithm works.</p>
<p>First of all it sorts the points by their curvature value.
It needs to be done because the region begins its growth from the point that has the minimum curvature value.
The reason for this is that the point with the minimum curvature is located in the flat area (growth from the flattest area
allows to reduce the total number of segments).</p>
<p>So we have the sorted cloud. Until there are unlabeled points in the cloud, algorithm picks up the point with minimum curvature value and starts the growth of the region. This process occurs as follows:</p>
<blockquote>
<div><ul>
<li><p class="first">The picked point is added to the set called seeds.</p>
</li>
<li><p class="first">For every seed point algorithm finds neighbouring points.</p>
<blockquote>
<div><ul class="simple">
<li>Every neighbour is tested for the angle between its normal and normal of the current seed point. If the angle is less than threshold value
then current point is added to the current region.</li>
<li>After that every neighbour is tested for the curvature value. If the curvature is less than threshold value then this point is added to the seeds.</li>
<li>Current seed is removed from the seeds.</li>
</ul>
</div></blockquote>
</li>
</ul>
</div></blockquote>
<p>If the seeds set becomes empty this means that the algorithm has grown the region and the process is repeated from the beginning.
You can find the pseudocode for the said algorithm below.</p>
<p>Inputs:</p>
<blockquote>
<div><ul class="simple">
<li><em>Point cloud</em> = <img class="math" src="_images/math/65d6b17699350b738003572a33a80c618658bf7e.png" alt="\{P\}"/></li>
<li><em>Point normals</em> = <img class="math" src="_images/math/965f1d1188ca407fa91a1324b28e9ccbfad460e5.png" alt="\{N\}"/></li>
<li><em>Points curvatures</em> = <img class="math" src="_images/math/dc125537611c00b86fd1133f43f663c38bc6bdbf.png" alt="\{c\}"/></li>
<li><em>Neighbour finding function</em> <img class="math" src="_images/math/df1460077c12f3e16de861af9958f88202fcb302.png" alt="\Omega(.)"/></li>
<li><em>Curvature threshold</em> <img class="math" src="_images/math/c27ab2170eaa92fc6e3caaea4a6816073548df5e.png" alt="c_{th}"/></li>
<li><em>Angle threshold</em> <img class="math" src="_images/math/2cbe408b0f6a68379eaddf58dd31cbac348f06b2.png" alt="\theta_{th}"/></li>
</ul>
</div></blockquote>
<p>Initialize:</p>
<blockquote>
<div><ul class="simple">
<li><em>Region list</em> <img class="math" src="_images/math/565067037adf45e71cc0df1e596ef1f2acb9a2e9.png" alt="{R}\leftarrow{\O}"/></li>
<li><em>Available points list</em> <img class="math" src="_images/math/737bb24233d3d44af99a6d225193f3e5fdfbb0f0.png" alt="\{A\}\leftarrow\{1,...,|P|\}"/></li>
</ul>
</div></blockquote>
<p>Algorithm:</p>
<blockquote>
<div><blockquote>
<div><ul>
<li><p class="first"><strong>While</strong> <img class="math" src="_images/math/0ff773039bd274397a8a58684ee6efe722de6a00.png" alt="\{A\}"/> <em>is not empty</em> <strong>do</strong></p>
<blockquote>
<div><ul>
<li><p class="first"><em>Current region</em> <img class="math" src="_images/math/aaabae806a93331ea1d92f764dca2666770d5d06.png" alt="\{R_c\}\leftarrow{\O}"/></p>
</li>
<li><p class="first"><em>Current seeds</em> <img class="math" src="_images/math/effde3c0e0555e1776fd133ab182cc1ec784b85e.png" alt="\{S_c\}\leftarrow{\O}"/></p>
</li>
<li><p class="first"><em>Point with minimum curvature in</em> <img class="math" src="_images/math/8ab094508faf0b7be3aaef6d3c53e160f6f1d4f0.png" alt="\{A\}\rightarrow P_{min}"/></p>
</li>
<li><p class="first"><img class="math" src="_images/math/bbfe2dd2eca08966b92b9b8ca5ff6c66dfeed3b1.png" alt="\{S_c\}\leftarrow\{S_c\}\cup P_{min}"/></p>
</li>
<li><p class="first"><img class="math" src="_images/math/0e5fc8be245b35eec0678c57a6ff8579a686d57c.png" alt="\{R_c\}\leftarrow\{R_c\}\cup P_{min}"/></p>
</li>
<li><p class="first"><img class="math" src="_images/math/4a9ffd2527aad1bb17a743b9ec4450db11c074f6.png" alt="\{A\}\leftarrow\{A\}\setminus P_{min}"/></p>
</li>
<li><p class="first"><strong>for</strong> <img class="math" src="_images/math/1ec93b5627cfed24f3bb09a36ada90e12b2a19e0.png" alt="i=0"/> <em>to</em> <strong>size</strong> ( <img class="math" src="_images/math/6abf7b5db39ce3035cd2d09bc2ede7d519d9a897.png" alt="\{S_c\}"/> ) <strong>do</strong></p>
<blockquote>
<div><ul>
<li><p class="first"><em>Find nearest neighbours of current seed point</em> <img class="math" src="_images/math/7f4ab27b5b6442228aebcc21e418ed9622f484ee.png" alt="\{B_c\}\leftarrow\Omega(S_c\{i\})"/></p>
</li>
<li><p class="first"><strong>for</strong> <img class="math" src="_images/math/a5e14847aa4d030fd7c68e786fbe806cabaf9236.png" alt="j=0"/> <em>to</em> <strong>size</strong> ( <img class="math" src="_images/math/3cc94bf99af867950fbf17c7874207ebe131771b.png" alt="\{B_c\}"/> ) <strong>do</strong></p>
<blockquote>
<div><ul>
<li><p class="first"><em>Current neighbour point</em> <img class="math" src="_images/math/636759d2cb5de2e6f3977d8da9cb99644000d85d.png" alt="P_j\leftarrow B_c\{j\}"/></p>
</li>
<li><p class="first"><strong>If</strong> <img class="math" src="_images/math/0ff773039bd274397a8a58684ee6efe722de6a00.png" alt="\{A\}"/> <em>contains</em> <img class="math" src="_images/math/42e5257e091f38b729f83a247b0e4cca3fa72db0.png" alt="P_j"/> <em>and</em> <img class="math" src="_images/math/996bfded5b52d8c532d4a561769227b879cfc6c1.png" alt="cos^{-1}(|(N\{S_c\{i\}\},N\{S_c\{j\}\})|)&lt;\theta_{th}"/> <strong>then</strong></p>
<blockquote>
<div><ul>
<li><p class="first"><img class="math" src="_images/math/b38ebcbd366ecd7431d8410bce8c358b3f53bf70.png" alt="\{R_c\}\leftarrow\{R_c\}\cup P_j"/></p>
</li>
<li><p class="first"><img class="math" src="_images/math/aa4359d394073798c663474547d1bd58b4ef5ecd.png" alt="\{A\}\leftarrow\{A\}\setminus P_j"/></p>
</li>
<li><p class="first"><strong>If</strong> <img class="math" src="_images/math/334a8f00081ae59c9e9377ec44687b05700e28da.png" alt="c\{P_j\}&lt;c_{th}"/> <strong>then</strong></p>
<blockquote>
<div><ul class="simple">
<li><img class="math" src="_images/math/74375bd078747c6a90ba0f04d2834e4c80f1ffd9.png" alt="\{S_c\}\leftarrow\{S_c\}\cup P_j"/></li>
</ul>
</div></blockquote>
</li>
<li><p class="first"><strong>end if</strong></p>
</li>
</ul>
</div></blockquote>
</li>
<li><p class="first"><strong>end if</strong></p>
</li>
</ul>
</div></blockquote>
</li>
<li><p class="first"><strong>end for</strong></p>
</li>
</ul>
</div></blockquote>
</li>
<li><p class="first"><strong>end for</strong></p>
</li>
<li><p class="first"><em>Add current region to global segment list</em> <img class="math" src="_images/math/2596ff8eaf48cf3468e202f1f113b584917abf9c.png" alt="\{R\}\leftarrow\{R\}\cup\{R_c\}"/></p>
</li>
</ul>
</div></blockquote>
</li>
</ul>
</div></blockquote>
<ul class="simple">
<li><strong>end while</strong></li>
<li><strong>Return</strong> <img class="math" src="_images/math/3683f44cdad2d76441db6cc320ade9b5b51f01da.png" alt="\{R\}"/></li>
</ul>
</div></blockquote>
</div>
<div class="section" id="the-code">
<h1>The code</h1>
<p>First of all you will need the point cloud for this tutorial.
<a class="reference external" href="https://raw.github.com/PointCloudLibrary/data/master/tutorials/region_growing_tutorial.pcd">This</a> is a good one for the purposes of the algorithm.
Next what you need to do is to create a file <tt class="docutils literal"><span class="pre">region_growing_segmentation.cpp</span></tt> in any editor you prefer and copy the following code inside of it:</p>
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
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
52
53
54
55
56
57
58
59
60
61
62
63
64
65
66
67
68
69
70
71
72
73</pre></div></td><td class="code"><div class="highlight"><pre><span class="cp">#include &lt;iostream&gt;</span>
<span class="cp">#include &lt;vector&gt;</span>
<span class="cp">#include &lt;pcl/point_types.h&gt;</span>
<span class="cp">#include &lt;pcl/io/pcd_io.h&gt;</span>
<span class="cp">#include &lt;pcl/search/search.h&gt;</span>
<span class="cp">#include &lt;pcl/search/kdtree.h&gt;</span>
<span class="cp">#include &lt;pcl/features/normal_3d.h&gt;</span>
<span class="cp">#include &lt;pcl/visualization/cloud_viewer.h&gt;</span>
<span class="cp">#include &lt;pcl/filters/passthrough.h&gt;</span>
<span class="cp">#include &lt;pcl/segmentation/region_growing.h&gt;</span>

<span class="kt">int</span>
<span class="nf">main</span> <span class="p">(</span><span class="kt">int</span> <span class="n">argc</span><span class="p">,</span> <span class="kt">char</span><span class="o">**</span> <span class="n">argv</span><span class="p">)</span>
<span class="p">{</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;::</span><span class="n">Ptr</span> <span class="n">cloud</span> <span class="p">(</span><span class="k">new</span> <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span><span class="p">);</span>
  <span class="k">if</span> <span class="p">(</span> <span class="n">pcl</span><span class="o">::</span><span class="n">io</span><span class="o">::</span><span class="n">loadPCDFile</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span> <span class="p">(</span><span class="s">&quot;region_growing_tutorial.pcd&quot;</span><span class="p">,</span> <span class="o">*</span><span class="n">cloud</span><span class="p">)</span> <span class="o">==</span> <span class="o">-</span><span class="mi">1</span><span class="p">)</span>
  <span class="p">{</span>
    <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;Cloud reading failed.&quot;</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
    <span class="k">return</span> <span class="p">(</span><span class="o">-</span><span class="mi">1</span><span class="p">);</span>
  <span class="p">}</span>

  <span class="n">pcl</span><span class="o">::</span><span class="n">search</span><span class="o">::</span><span class="n">Search</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;::</span><span class="n">Ptr</span> <span class="n">tree</span> <span class="o">=</span> <span class="n">boost</span><span class="o">::</span><span class="n">shared_ptr</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">search</span><span class="o">::</span><span class="n">Search</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span> <span class="o">&gt;</span> <span class="p">(</span><span class="k">new</span> <span class="n">pcl</span><span class="o">::</span><span class="n">search</span><span class="o">::</span><span class="n">KdTree</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span><span class="p">);</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">Normal</span><span class="o">&gt;::</span><span class="n">Ptr</span> <span class="n">normals</span> <span class="p">(</span><span class="k">new</span> <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">Normal</span><span class="o">&gt;</span><span class="p">);</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">NormalEstimation</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="p">,</span> <span class="n">pcl</span><span class="o">::</span><span class="n">Normal</span><span class="o">&gt;</span> <span class="n">normal_estimator</span><span class="p">;</span>
  <span class="n">normal_estimator</span><span class="p">.</span><span class="n">setSearchMethod</span> <span class="p">(</span><span class="n">tree</span><span class="p">);</span>
  <span class="n">normal_estimator</span><span class="p">.</span><span class="n">setInputCloud</span> <span class="p">(</span><span class="n">cloud</span><span class="p">);</span>
  <span class="n">normal_estimator</span><span class="p">.</span><span class="n">setKSearch</span> <span class="p">(</span><span class="mi">50</span><span class="p">);</span>
  <span class="n">normal_estimator</span><span class="p">.</span><span class="n">compute</span> <span class="p">(</span><span class="o">*</span><span class="n">normals</span><span class="p">);</span>

  <span class="n">pcl</span><span class="o">::</span><span class="n">IndicesPtr</span> <span class="n">indices</span> <span class="p">(</span><span class="k">new</span> <span class="n">std</span><span class="o">::</span><span class="n">vector</span> <span class="o">&lt;</span><span class="kt">int</span><span class="o">&gt;</span><span class="p">);</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">PassThrough</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span> <span class="n">pass</span><span class="p">;</span>
  <span class="n">pass</span><span class="p">.</span><span class="n">setInputCloud</span> <span class="p">(</span><span class="n">cloud</span><span class="p">);</span>
  <span class="n">pass</span><span class="p">.</span><span class="n">setFilterFieldName</span> <span class="p">(</span><span class="s">&quot;z&quot;</span><span class="p">);</span>
  <span class="n">pass</span><span class="p">.</span><span class="n">setFilterLimits</span> <span class="p">(</span><span class="mf">0.0</span><span class="p">,</span> <span class="mf">1.0</span><span class="p">);</span>
  <span class="n">pass</span><span class="p">.</span><span class="n">filter</span> <span class="p">(</span><span class="o">*</span><span class="n">indices</span><span class="p">);</span>

  <span class="n">pcl</span><span class="o">::</span><span class="n">RegionGrowing</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="p">,</span> <span class="n">pcl</span><span class="o">::</span><span class="n">Normal</span><span class="o">&gt;</span> <span class="n">reg</span><span class="p">;</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setMinClusterSize</span> <span class="p">(</span><span class="mi">50</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setMaxClusterSize</span> <span class="p">(</span><span class="mi">1000000</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setSearchMethod</span> <span class="p">(</span><span class="n">tree</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setNumberOfNeighbours</span> <span class="p">(</span><span class="mi">30</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setInputCloud</span> <span class="p">(</span><span class="n">cloud</span><span class="p">);</span>
  <span class="c1">//reg.setIndices (indices);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setInputNormals</span> <span class="p">(</span><span class="n">normals</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setSmoothnessThreshold</span> <span class="p">(</span><span class="mf">3.0</span> <span class="o">/</span> <span class="mf">180.0</span> <span class="o">*</span> <span class="n">M_PI</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setCurvatureThreshold</span> <span class="p">(</span><span class="mf">1.0</span><span class="p">);</span>

  <span class="n">std</span><span class="o">::</span><span class="n">vector</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointIndices</span><span class="o">&gt;</span> <span class="n">clusters</span><span class="p">;</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">extract</span> <span class="p">(</span><span class="n">clusters</span><span class="p">);</span>

  <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;Number of clusters is equal to &quot;</span> <span class="o">&lt;&lt;</span> <span class="n">clusters</span><span class="p">.</span><span class="n">size</span> <span class="p">()</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
  <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;First cluster has &quot;</span> <span class="o">&lt;&lt;</span> <span class="n">clusters</span><span class="p">[</span><span class="mi">0</span><span class="p">].</span><span class="n">indices</span><span class="p">.</span><span class="n">size</span> <span class="p">()</span> <span class="o">&lt;&lt;</span> <span class="s">&quot; points.&quot;</span> <span class="o">&lt;&lt;</span> <span class="n">endl</span><span class="p">;</span>
  <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;These are the indices of the points of the initial&quot;</span> <span class="o">&lt;&lt;</span>
    <span class="n">std</span><span class="o">::</span><span class="n">endl</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;cloud that belong to the first cluster:&quot;</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
  <span class="kt">int</span> <span class="n">counter</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span>
  <span class="k">while</span> <span class="p">(</span><span class="n">counter</span> <span class="o">&lt;</span> <span class="n">clusters</span><span class="p">[</span><span class="mi">0</span><span class="p">].</span><span class="n">indices</span><span class="p">.</span><span class="n">size</span> <span class="p">())</span>
  <span class="p">{</span>
    <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="n">clusters</span><span class="p">[</span><span class="mi">0</span><span class="p">].</span><span class="n">indices</span><span class="p">[</span><span class="n">counter</span><span class="p">]</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;, &quot;</span><span class="p">;</span>
    <span class="n">counter</span><span class="o">++</span><span class="p">;</span>
    <span class="k">if</span> <span class="p">(</span><span class="n">counter</span> <span class="o">%</span> <span class="mi">10</span> <span class="o">==</span> <span class="mi">0</span><span class="p">)</span>
      <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
  <span class="p">}</span>
  <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>

  <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZRGB</span><span class="o">&gt;::</span><span class="n">Ptr</span> <span class="n">colored_cloud</span> <span class="o">=</span> <span class="n">reg</span><span class="p">.</span><span class="n">getColoredCloud</span> <span class="p">();</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">visualization</span><span class="o">::</span><span class="n">CloudViewer</span> <span class="n">viewer</span> <span class="p">(</span><span class="s">&quot;Cluster viewer&quot;</span><span class="p">);</span>
  <span class="n">viewer</span><span class="p">.</span><span class="n">showCloud</span><span class="p">(</span><span class="n">colored_cloud</span><span class="p">);</span>
  <span class="k">while</span> <span class="p">(</span><span class="o">!</span><span class="n">viewer</span><span class="p">.</span><span class="n">wasStopped</span> <span class="p">())</span>
  <span class="p">{</span>
  <span class="p">}</span>

  <span class="k">return</span> <span class="p">(</span><span class="mi">0</span><span class="p">);</span>
<span class="p">}</span>
</pre></div>
</td></tr></table></div>
</div>
<div class="section" id="the-explanation">
<h1>The explanation</h1>
<p>Now let&#8217;s study out what is the purpose of this code. First few lines will be omitted, because they are obvious.</p>
<p>First lines that are of interest are these:</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;::</span><span class="n">Ptr</span> <span class="n">cloud</span> <span class="p">(</span><span class="k">new</span> <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span><span class="p">);</span>
  <span class="k">if</span> <span class="p">(</span> <span class="n">pcl</span><span class="o">::</span><span class="n">io</span><span class="o">::</span><span class="n">loadPCDFile</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span> <span class="p">(</span><span class="s">&quot;region_growing_tutorial.pcd&quot;</span><span class="p">,</span> <span class="o">*</span><span class="n">cloud</span><span class="p">)</span> <span class="o">==</span> <span class="o">-</span><span class="mi">1</span><span class="p">)</span>
  <span class="p">{</span>
    <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;Cloud reading failed.&quot;</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
    <span class="k">return</span> <span class="p">(</span><span class="o">-</span><span class="mi">1</span><span class="p">);</span>
  <span class="p">}</span>
</pre></div>
</div>
<p>They are simply loading the cloud from the .pcd file. No doubt that you saw how it is done hundreds of times, so let&#8217;s move on.</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">pcl</span><span class="o">::</span><span class="n">search</span><span class="o">::</span><span class="n">Search</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;::</span><span class="n">Ptr</span> <span class="n">tree</span> <span class="o">=</span> <span class="n">boost</span><span class="o">::</span><span class="n">shared_ptr</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">search</span><span class="o">::</span><span class="n">Search</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span> <span class="o">&gt;</span> <span class="p">(</span><span class="k">new</span> <span class="n">pcl</span><span class="o">::</span><span class="n">search</span><span class="o">::</span><span class="n">KdTree</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span><span class="p">);</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">Normal</span><span class="o">&gt;::</span><span class="n">Ptr</span> <span class="n">normals</span> <span class="p">(</span><span class="k">new</span> <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">Normal</span><span class="o">&gt;</span><span class="p">);</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">NormalEstimation</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="p">,</span> <span class="n">pcl</span><span class="o">::</span><span class="n">Normal</span><span class="o">&gt;</span> <span class="n">normal_estimator</span><span class="p">;</span>
  <span class="n">normal_estimator</span><span class="p">.</span><span class="n">setSearchMethod</span> <span class="p">(</span><span class="n">tree</span><span class="p">);</span>
  <span class="n">normal_estimator</span><span class="p">.</span><span class="n">setInputCloud</span> <span class="p">(</span><span class="n">cloud</span><span class="p">);</span>
  <span class="n">normal_estimator</span><span class="p">.</span><span class="n">setKSearch</span> <span class="p">(</span><span class="mi">50</span><span class="p">);</span>
  <span class="n">normal_estimator</span><span class="p">.</span><span class="n">compute</span> <span class="p">(</span><span class="o">*</span><span class="n">normals</span><span class="p">);</span>
</pre></div>
</div>
<p>As mentioned before, the algorithm requires normals. Here the <tt class="docutils literal"><span class="pre">pcl::NormalEstimation</span></tt> class is used to compute them.
To learn more about how it is done you should take a look at the <a class="reference internal" href="normal_estimation.php#normal-estimation"><em>Estimating Surface Normals in a PointCloud</em></a> tutorial in the <strong>Features</strong> section.</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">pcl</span><span class="o">::</span><span class="n">IndicesPtr</span> <span class="n">indices</span> <span class="p">(</span><span class="k">new</span> <span class="n">std</span><span class="o">::</span><span class="n">vector</span> <span class="o">&lt;</span><span class="kt">int</span><span class="o">&gt;</span><span class="p">);</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">PassThrough</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="o">&gt;</span> <span class="n">pass</span><span class="p">;</span>
  <span class="n">pass</span><span class="p">.</span><span class="n">setInputCloud</span> <span class="p">(</span><span class="n">cloud</span><span class="p">);</span>
  <span class="n">pass</span><span class="p">.</span><span class="n">setFilterFieldName</span> <span class="p">(</span><span class="s">&quot;z&quot;</span><span class="p">);</span>
  <span class="n">pass</span><span class="p">.</span><span class="n">setFilterLimits</span> <span class="p">(</span><span class="mf">0.0</span><span class="p">,</span> <span class="mf">1.0</span><span class="p">);</span>
  <span class="n">pass</span><span class="p">.</span><span class="n">filter</span> <span class="p">(</span><span class="o">*</span><span class="n">indices</span><span class="p">);</span>
</pre></div>
</div>
<p>These lines are given only for example. You can safely comment this part. Insofar as <tt class="docutils literal"><span class="pre">pcl::RegionGrowing</span></tt> is derived from <tt class="docutils literal"><span class="pre">pcl::PCLBase</span></tt>,
it can work with indices. It means you can point that you need to segment only
those points that are listed in the indices array instead of the whole point cloud.</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">pcl</span><span class="o">::</span><span class="n">RegionGrowing</span><span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZ</span><span class="p">,</span> <span class="n">pcl</span><span class="o">::</span><span class="n">Normal</span><span class="o">&gt;</span> <span class="n">reg</span><span class="p">;</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setMinClusterSize</span> <span class="p">(</span><span class="mi">50</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setMaxClusterSize</span> <span class="p">(</span><span class="mi">1000000</span><span class="p">);</span>
</pre></div>
</div>
<p>You have finally reached the part where <tt class="docutils literal"><span class="pre">pcl::RegionGrowing</span></tt> is instantiated. It is a template class that have two parameters:</p>
<ul class="simple">
<li>PointT - type of points to use(in the given example it is <tt class="docutils literal"><span class="pre">pcl::PointXYZ</span></tt>)</li>
<li>NormalT - type of normals to use(in the given example it is <tt class="docutils literal"><span class="pre">pcl::Normal</span></tt>)</li>
</ul>
<p>After that minimum and maximum cluster sizes are set. It means that
after the segmentation is done all clusters that have less points then was set as minimum(or have more than maximum) will be discarded.
The default values for minimum and maximum are 1 and &#8216;as much as possible&#8217; respectively.</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">reg</span><span class="p">.</span><span class="n">setSearchMethod</span> <span class="p">(</span><span class="n">tree</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setNumberOfNeighbours</span> <span class="p">(</span><span class="mi">30</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setInputCloud</span> <span class="p">(</span><span class="n">cloud</span><span class="p">);</span>
  <span class="c1">//reg.setIndices (indices);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setInputNormals</span> <span class="p">(</span><span class="n">normals</span><span class="p">);</span>
</pre></div>
</div>
<p>The algorithm needs K nearest search in its internal structure, so here is the place where a search method is provided
and number of neighbours is set. After that it receives the cloud that must be segmented, point indices and normals.</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">reg</span><span class="p">.</span><span class="n">setSmoothnessThreshold</span> <span class="p">(</span><span class="mf">3.0</span> <span class="o">/</span> <span class="mf">180.0</span> <span class="o">*</span> <span class="n">M_PI</span><span class="p">);</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">setCurvatureThreshold</span> <span class="p">(</span><span class="mf">1.0</span><span class="p">);</span>
</pre></div>
</div>
<p>This two lines are most important part in the algorithm initialization, because they are responsible for the mentioned smoothness constraint.
First method sets the angle in radians that will be used as the allowable range for the normals deviation.
If the deviation between points normals is less than smoothness threshold then they are suggested to be in the same cluster
(new point - the tested one - will be added to the cluster).
The second one is responsible for curvature threshold. If two points have a small normals deviation then the disparity between their curvatures is tested.
And if this value is less than curvature threshold then the algorithm will continue the growth of the cluster using new added point.</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">std</span><span class="o">::</span><span class="n">vector</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointIndices</span><span class="o">&gt;</span> <span class="n">clusters</span><span class="p">;</span>
  <span class="n">reg</span><span class="p">.</span><span class="n">extract</span> <span class="p">(</span><span class="n">clusters</span><span class="p">);</span>
</pre></div>
</div>
<p>This method simply launches the segmentation algorithm. After its work it will return clusters array.</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;Number of clusters is equal to &quot;</span> <span class="o">&lt;&lt;</span> <span class="n">clusters</span><span class="p">.</span><span class="n">size</span> <span class="p">()</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
  <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;First cluster has &quot;</span> <span class="o">&lt;&lt;</span> <span class="n">clusters</span><span class="p">[</span><span class="mi">0</span><span class="p">].</span><span class="n">indices</span><span class="p">.</span><span class="n">size</span> <span class="p">()</span> <span class="o">&lt;&lt;</span> <span class="s">&quot; points.&quot;</span> <span class="o">&lt;&lt;</span> <span class="n">endl</span><span class="p">;</span>
  <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;These are the indices of the points of the initial&quot;</span> <span class="o">&lt;&lt;</span>
    <span class="n">std</span><span class="o">::</span><span class="n">endl</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;cloud that belong to the first cluster:&quot;</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
  <span class="kt">int</span> <span class="n">counter</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span>
  <span class="k">while</span> <span class="p">(</span><span class="n">counter</span> <span class="o">&lt;</span> <span class="n">clusters</span><span class="p">[</span><span class="mi">0</span><span class="p">].</span><span class="n">indices</span><span class="p">.</span><span class="n">size</span> <span class="p">())</span>
  <span class="p">{</span>
    <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="n">clusters</span><span class="p">[</span><span class="mi">0</span><span class="p">].</span><span class="n">indices</span><span class="p">[</span><span class="n">counter</span><span class="p">]</span> <span class="o">&lt;&lt;</span> <span class="s">&quot;, &quot;</span><span class="p">;</span>
    <span class="n">counter</span><span class="o">++</span><span class="p">;</span>
    <span class="k">if</span> <span class="p">(</span><span class="n">counter</span> <span class="o">%</span> <span class="mi">10</span> <span class="o">==</span> <span class="mi">0</span><span class="p">)</span>
      <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
  <span class="p">}</span>
  <span class="n">std</span><span class="o">::</span><span class="n">cout</span> <span class="o">&lt;&lt;</span> <span class="n">std</span><span class="o">::</span><span class="n">endl</span><span class="p">;</span>
</pre></div>
</div>
<p>These lines are simple enough, so they won&#8217;t be commented. They are intended for those who are not familiar with how to work with <tt class="docutils literal"><span class="pre">pcl::PointIndices</span></tt>
and how to access its elements.</p>
<div class="highlight-cpp"><div class="highlight"><pre>  <span class="n">pcl</span><span class="o">::</span><span class="n">PointCloud</span> <span class="o">&lt;</span><span class="n">pcl</span><span class="o">::</span><span class="n">PointXYZRGB</span><span class="o">&gt;::</span><span class="n">Ptr</span> <span class="n">colored_cloud</span> <span class="o">=</span> <span class="n">reg</span><span class="p">.</span><span class="n">getColoredCloud</span> <span class="p">();</span>
  <span class="n">pcl</span><span class="o">::</span><span class="n">visualization</span><span class="o">::</span><span class="n">CloudViewer</span> <span class="n">viewer</span> <span class="p">(</span><span class="s">&quot;Cluster viewer&quot;</span><span class="p">);</span>
  <span class="n">viewer</span><span class="p">.</span><span class="n">showCloud</span><span class="p">(</span><span class="n">colored_cloud</span><span class="p">);</span>
  <span class="k">while</span> <span class="p">(</span><span class="o">!</span><span class="n">viewer</span><span class="p">.</span><span class="n">wasStopped</span> <span class="p">())</span>
  <span class="p">{</span>
  <span class="p">}</span>

  <span class="k">return</span> <span class="p">(</span><span class="mi">0</span><span class="p">);</span>
<span class="p">}</span>
</pre></div>
</div>
<p>The <tt class="docutils literal"><span class="pre">pcl::RegionGrowing</span></tt> class provides a method that returns the colored cloud where each cluster has its own color.
So in this part of code the <tt class="docutils literal"><span class="pre">pcl::visualization::CloudViewer</span></tt> is instanciated for viewing the result of the segmentation - the same colored cloud.
You can learn more about cloud visualization in the <a class="reference internal" href="cloud_viewer.php#cloud-viewer"><em>The CloudViewer</em></a> tutorial.</p>
</div>
<div class="section" id="compiling-and-running-the-program">
<h1>Compiling and running the program</h1>
<p>Add the following lines to your CMakeLists.txt file:</p>
<div class="highlight-cmake"><table class="highlighttable"><tr><td class="linenos"><div class="linenodiv"><pre> 1
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
12</pre></div></td><td class="code"><div class="highlight"><pre><span class="nb">cmake_minimum_required</span><span class="p">(</span><span class="s">VERSION</span> <span class="s">2.8</span> <span class="s">FATAL_ERROR</span><span class="p">)</span>

<span class="nb">project</span><span class="p">(</span><span class="s">region_growing_segmentation</span><span class="p">)</span>

<span class="nb">find_package</span><span class="p">(</span><span class="s">PCL</span> <span class="s">1.5</span> <span class="s">REQUIRED</span><span class="p">)</span>

<span class="nb">include_directories</span><span class="p">(</span><span class="o">${</span><span class="nv">PCL_INCLUDE_DIRS</span><span class="o">}</span><span class="p">)</span>
<span class="nb">link_directories</span><span class="p">(</span><span class="o">${</span><span class="nv">PCL_LIBRARY_DIRS</span><span class="o">}</span><span class="p">)</span>
<span class="nb">add_definitions</span><span class="p">(</span><span class="o">${</span><span class="nv">PCL_DEFINITIONS</span><span class="o">}</span><span class="p">)</span>

<span class="nb">add_executable</span> <span class="p">(</span><span class="s">region_growing_segmentation</span> <span class="s">region_growing_segmentation.cpp</span><span class="p">)</span>
<span class="nb">target_link_libraries</span> <span class="p">(</span><span class="s">region_growing_segmentation</span> <span class="o">${</span><span class="nv">PCL_LIBRARIES</span><span class="o">}</span><span class="p">)</span>
</pre></div>
</td></tr></table></div>
<p>After you have made the executable, you can run it. Simply do:</p>
<div class="highlight-python"><div class="highlight"><pre>$ ./region_growing_segmentation
</pre></div>
</div>
<p>After the segmentation the cloud viewer window will be opened and you will see something similar to those images:</p>
<a class="reference internal image-reference" href="_images/region_growing_segmentation_1.jpg"><img alt="_images/region_growing_segmentation_1.jpg" src="_images/region_growing_segmentation_1.jpg" style="height: 200px;" /></a>
<a class="reference internal image-reference" href="_images/region_growing_segmentation_2.jpg"><img alt="_images/region_growing_segmentation_2.jpg" src="_images/region_growing_segmentation_2.jpg" style="height: 200px;" /></a>
<p>On the last image you can see that the colored cloud has many red points. This means that these points belong to the clusters
that were rejected, because they had too much/little points.</p>
<a class="reference internal image-reference" href="_images/region_growing_segmentation_3.jpg"><img alt="_images/region_growing_segmentation_3.jpg" src="_images/region_growing_segmentation_3.jpg" style="height: 300px;" /></a>
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