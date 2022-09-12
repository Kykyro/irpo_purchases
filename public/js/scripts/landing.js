// 3D Scroll

let zSpacing = -1000,
		lastPos = zSpacing / 5,
		$frames = document.getElementsByClassName('frame'),
		frames = Array.from($frames),
		zVals = []

window.onscroll = function() {

	let top = document.documentElement.scrollTop,
			delta = lastPos - top

	lastPos = top

	frames.forEach(function(n, i) {

		zVals.push((i * zSpacing) + zSpacing)
		zVals[i] += delta * -6
		let frame = frames[i],
				transform = `translateZ(${zVals[i]}px)`,
				opacity = zVals[i] < Math.abs(zSpacing) / 1.8 ? 1 : 0
		frame.setAttribute('style', `transform: ${transform}; opacity: ${opacity}`)
		if (opacity == 0) {

			setTimeout(() => {

				frame.style.visibility = 'collapse'

			}, 10)

		} else if(opacity == 1) {

			setTimeout(() => {

				frame.style.visibility = 'visible'

			}, 10)

		}
	})

}

window.scrollTo(0, 1)

