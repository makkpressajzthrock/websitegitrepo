Smart cache: 

By default, bunny.net will cache all resources returned by the origin server that contain cacheable headers such as Cache-Control or Expires. In case of server misconfiguration, this can result in incorrect caching of sensitive or personal information.

---------------------------------------------------------------------------------

Vary Cache: 

The Vary Cache settings help you configure how the internal caching key for each request is configured. bunny.net does not follow the standard HTTP Vary header. Instead, multiple configuration options are provided in the Pull Zone Cache settings allow you to configure the caching behavior.

By default, all Vary Cache settings are disabled. Each URL is treated as a unique cache key based on the path and file name of the request. To add additional capabilities, you can enable different Vary Cache settings and generate the caching key based on different parameters.

---------------------------------------------------------------------------------

Origin Shield:

Origin Shield is a secondary caching layer sitting between the CDN edge PoPs and the origin. It serves as a single caching point to reduce the amount of traffic hitting the origin in case the same files are requested from around the world.

Origin Shield should not be confused with a Web Application Firewall (WAF). It will not block or filter requests going to the origin. It is strictly designed to minimize the amount of traffic.



---------------------------------------------------------------------------------

Request Coalescing: 

Request coalescing combines multiple simultaneous requests to the same resource into a single request going to the origin. If multiple requests come in at the same time, they will be automatically merged. Once the request from the origin completes, the response will be streamed in real-time to all waiting connections for that request path.

Request Coalescing happens on each of the bunny.net edge servers, meaning that multiple requests can still reach your server. The number of requests can further be reduced by combining Request Coalescing with the origin shield. This will effectively cause Bunny CDN to only send a single request at a time to the origin for that specific request path.

