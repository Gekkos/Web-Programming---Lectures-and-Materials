self.addEventListener('message', function(e) {
	// start calculating primes
	var isPrime = function(n) {
		if( n <= 1) {
			return false;
		}

		for(var i = 2; i*i <= n; i++) {
			if(n % i === 0) {
				return false;
			}
		}

		return true;
	};

	var forever = 2;
	var primeCount = 0;
	var howMuch = e.data;

	while(forever++ && primeCount != howMuch) {
		if(isPrime(forever)) {
			primeCount++;
  			// self.postMessage(forever);
		}
	}
	self.postMessage("I have found them all !");
	self.close();
}, false);