deny from all
<Files ~ "(?i)^.*\.(jpg|jpeg|gif|png|bmp|tiff|svg|pdf|mov|mpeg|mp4|avi|mpg|wma|flv|webm|ogg)$">
	order deny,allow
	allow from all
</Files>
