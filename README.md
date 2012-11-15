Bluefin AWS Image Server
=============

Open Source on-the-fly image server.  Resize your [S3](http://aws.amazon.com/s3/) images on the fly using [ImageMagick](http://www.imagemagick.org/script/index.php), then cache them using [CloudFront](http://aws.amazon.com/cloudfront/).

After uploading your full size image to [S3](http://aws.amazon.com/s3/) you can request a resized image to place on your page.


Parameters
-------

<ul>
<li>s3path (required, valid paths defined in <a href="bluefin_aws_image_server/blob/master/www/valid-s3-paths.ini">valid-s3-paths.ini</a>)</li>
<li>size (default=200)</li>
<li>square (true | false, default=false)</li>
</ul>

Example URLs
-------

    http://domain.com/images/test-image.jpg?s3path=/bluefin_aws_image_server/&size=400&square=true

    http://domain.com/images/test-image.jpg?s3path=/bluefin_aws_image_server/&size=400
    
Setup
-------

### What you need
+ [S3](http://aws.amazon.com/s3/) bucket with <strong>* public *</strong> images
+ [EC2](http://aws.amazon.com/ec2/) server (or [auto scaling](http://aws.amazon.com/autoscaling/) group)
 + You can use the following EC2 AMI: ami-1a75f173
 + You could use you own server.  See the server configuration. <a href="bluefin_aws_image_server/blob/master/config/ec2_config.txt">ec2_config.txt</a>
+ [CloudFront](http://aws.amazon.com/cloudfront/) to cach your images (Forwarded Query Strings)


### Valid S3 Paths

Configure the <a href="bluefin_aws_image_server/blob/master/www/valid-s3-paths.ini">valid-s3-paths.ini</a> so that only your images can be accessed.   

Amazon AMI
-------

AMI ID: ami-1a75f173

AMI Name: bluefin_aws_image_server
    
Support
-------

support@bluefinengineering.com

http://bluefinengineering.com/
    
Collaborators Welcome!
-------

If you would like to contribute to this open-source project please let me know (support@bluefinengineering.com).  
For example if you wish the service performed another operation on an image, please write the code and submit the update.
