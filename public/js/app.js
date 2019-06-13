const posts = document.getElementById('posts');

if (posts) {
	posts.addEventListener('click', (e) => {
		if (e.target.className === 'btn btn-primary delete-post') {
			if (confirm('Are you sure?')) {
				const id = e.target.getAttribute('data-id');

				fetch(`/posts/${id}/destroy`, {
					method: 'DELETE'
				}).then(res => window.location.reload());
			}
		}
	});
}