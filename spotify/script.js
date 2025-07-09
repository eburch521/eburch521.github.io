const url = 'https://spotify-statistics-and-stream-count.p.rapidapi.com/artist/6GAZhXqlPQdf3mzK6hvGM9';
const options = {
	method: 'GET',
	headers: {
		'x-rapidapi-key': '6aa5b41c0cmsha4e32e57a73b310p1bc143jsn80cafbfd4840',
		'x-rapidapi-host': 'spotify-statistics-and-stream-count.p.rapidapi.com'
	}
};

function showInfo(data) {
    console.table(data.city);
} 

try {
	//fetch(url, options)\
	fetch('toddwait.json')
	.then(response => response.json())
	.then(data => {
		console.table(data);
		const dataDisplay = document.getElementById('dataDisplay');
		
		// display artist name
		const artist = document.createElement('div');
		artist.classList.add('toppers');
		artist.innerText = `Artist: ${data.name}`;
		dataDisplay.appendChild(artist);

		//display followers
		const followers = document.createElement('div');
		followers.classList.add('toppers');
		followers.innerText = `Followers: ${data.followers}`;
		dataDisplay.appendChild(followers);

		//display monthly listeners
		const monthlyListeners = document.createElement('div');
		monthlyListeners.classList.add('toppers');
		monthlyListeners.innerText = `Monthly Listeners: ${data.monthlyListeners}`;
		dataDisplay.appendChild(monthlyListeners);
		
		//create list of top 5 cities
		const topCities = document.createElement('div');
		topCities.classList.add('toppers');
		topCities.innerText = 'Top Cities: ';
		dataDisplay.appendChild(topCities);
		data.topCities.forEach(city => {
			let li = document.createElement('li');
			li.classList.add('list-item');
			li.innerText = `${city.city}, ${city.country} - ${city.numberOfListeners} listeners`;
			dataDisplay.appendChild(li);
		});

		//create list of top tracks
		const topTracks = document.createElement('div');
		topTracks.classList.add('toppers');
		topTracks.innerText = 'Top Tracks: ';
		dataDisplay.appendChild(topTracks);
		data.topTracks.forEach(track => {
			let li = document.createElement('li');
			li.classList.add('list-item');
			li.innerText = `${track.name} - ${track.streamCount} streams`;
			dataDisplay.appendChild(li);
		});



  });
} catch (error) {
	console.error(error);
}


/*
const instaUrl = 'https://graph.instagram.com/v22.0/517003635136345/insights?metric=engaged_audience_demographics&period=lifetime&timeframe=last_30_days&metric_type=total_value&breakdown=city&access_token=EAAHvoPWGB7YBOzWTfzHcUIvNnUewerCwbKIQYK7aOm9C0VUZCaS8ZCGsOWZCv0DZCvPhwu1982RZBwSvLhkftZBmIVRMHrRJBNDiUnPLPKumlO2TBl7SZCZCwZAGfcU6JOxT3RcuIoZBtpXrZCde2BPRCkzPYVZCYNKa0hmJNifkcZCLiRWD7seqVHZCAK7c1dwpve';

try {
	const response = fetch(instaUrl)
	.then(response => response.json())
  .then(data => console.log(data));
	console.log(response);
} catch (error) {
	console.error(error);
}*/