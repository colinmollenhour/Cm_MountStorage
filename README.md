Magento "Mount Storage"
=======================

Magento already supports a central storage backend out of the box, but it uses MySQL which is
not ideal for storing large amounts of files (taking and restoring backups is slow and not very incremental).
However, the "lazy load" approach it uses with `get.php` is a wonderful overall design for easy management,
high availability, and local disk performance. This extension rewrites the Magento core model to instead
treat a separate mount point as the central storage, allowing you to use any filesystem that can be mounted
to a normal mount point without depending directly on that mount point and therefore being subject to
the downsides of a particular backend.

For example, NFS is a great way to share files on a local network, but then it becomes a single point
of failure. Using this extension you can point it to the NFS mount as the central storage location and
you will get essentially a local-disk cache of the NFS files that are used making the NFS mount no longer
a SPOF (assuming files are already cached when the NFS mount fails)!

Other examples of filesystems that could be used with this extension:

 - GlusterFS
 - Ceph
 - SSHfs (SSH over FUSE)
 - Amazon S3 over FUSE
 - etc...

Note, I built this to use with NFS and have only tested it with NFS, there could be side-effects, particularly
with race conditions between two writes to the same file depending on the properties of the remote filesystem being used.

Additionally, when using anything other than the local filesystem you should be aware that files are cached until they expire
and the expiration time is set by the "Environment Update Time" configuration. If you need to serve files from the media
directory that change more frequently than you set this expiration time you are possibly better off adjusting your web server
configuration to serve these files directly from the shared storage instead of the cache and keeping a longer expiration time.

Installation
------------

Install via modman or composer and configure in System > Configuration > System > Storage Configuration for Media.

Credit
------

I used [Thai's S3](https://github.com/thaiphan/magento-s3) extension code to save some time with the boilerplate model rewrites
and such. Thanks, Thai Phan for sharing the clean extension code!
