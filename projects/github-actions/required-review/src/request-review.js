const core = require( '@actions/core' );
const github = require( '@actions/github' );
const getUsername = require( './get-username.js' );

/**
 * Request review from the given team
 *
 * @param {string} team - GitHub team slug, or @ followed by a GitHub user name.
 */
async function requestReviewer( teams ) {
	const octokit = github.getOctokit( core.getInput( 'token', { required: true } ) );
	const owner = github.context.payload.repository.owner.login;
	const repo = github.context.payload.repository.name;
	const pr = github.context.payload.pull_request.number;

	let userReviews = []
	let teamReviews = []

	for (var i = 0; i < teams.length; i++) {
		const t = teams[ i ]
		if ( t.startsWith( '@' ) ) {
			userReviews.push( t.slice( 1 ) )
		} else {
			teamReviews.push( t )
		}
	}

	try {
		await octokit.rest.pulls.requestReviewers( {
			owner: owner,
			repo: repo,
			pull_number: pr,
			reviewers: userReviews,
			team_reviewers: teamReviews
		} )
		core.info( `Requested review(s) from ${ teams }` );
	} catch ( err ) {
		throw new Error( `Unable to request review.\n  Error: ${err}` );
	}
}

module.exports = requestReviewer;
