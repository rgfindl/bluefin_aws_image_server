Bluefin AWS Image Server
=============

Open Source on-the-fly image server.  Resize your [S3](http://aws.amazon.com/s3/) images on the fly, then cache them using [CloudFront](http://aws.amazon.com/cloudfront/).

After uploading your full size image to [S3](http://aws.amazon.com/s3/) you can request a resized image to place on your page.

Ex.  User uploads their profile picture.  Display a small square profile picture next to their name.  
When a user clicks on the profile picture the large image is displayed.

Parameters
-------

<ul>
<li>s3path (required, valid paths defined in <a href="www/valid-s3-paths.ini">valid-s3-paths.ini</a>)</li>
<li>size (default=200)</li>
<li>square (true | false, default=false)</li>
</ul>

Example URLs
-------

    http://domain.com/images/test-image.jpg?s3path=/bluefin_aws_image_server/&size=400&square=true

    http://domain.com/images/test-image.jpg?s3path=/bluefin_aws_image_server/&size=400

Amazon AMI
-------

AMI ID: ami-1a75f173

AMI Name: bluefin_aws_image_server
