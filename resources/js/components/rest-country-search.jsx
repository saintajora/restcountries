/**
 * React Components for the search engine
 * 
 * @author Ben Goetzinger
 * @package RestComponents
 */

import React, { Component } from 'react';
import ReactDOM from 'react-dom';

// Main Search component
class RestCountrySearch extends Component {

	constructor(props) {
		super(props);
		this.state = {
			query: '',
			orderBy: 'name',
			maxResults: 50,
			results: [],
			regions: {},
			subregions: {},
			error: false
		};

		this.queryChange = this.queryChange.bind(this);
		this.orderByChange = this.orderByChange.bind(this);
		this.maxResultsChange = this.maxResultsChange.bind(this);
		this.handleSubmit = this.handleSubmit.bind(this);
	}

	/**
	 * Handle changes in the search bar.
	 * 
	 * @param Event e 
	 */
	queryChange(e) {
		this.setState({ query: e.target.value });
	}

	/**
	 * Handle changes in the order by menu.
	 * 
	 * @param Event e 
	 */
	orderByChange(e) {
		this.setState({ orderBy: e.target.value });
	}

	/**
	 * Handle changes in the max results menu.
	 * 
	 * @param Event e 
	 */
	maxResultsChange(e) {
		this.setState({ maxResults: e.target.value });
	}

	/**
	 * Show an error message.
	 * 
	 * @param string msg
	 */
	showErrorMessage(msg) {
		this.setState({ error: msg });
		var _this = this;
		setTimeout(function () { _this.setState({ error: false }) }, 6000);
	}

	/**
	 * Calls the server and processes the resulting data.
	 * 
	 * @param Event e 
	 */
	handleSubmit(e) {
		e.preventDefault();
		this.setState({ results: [], regions: {}, subregions: [] });

		if (this.state.query == '') {
			this.showErrorMessage("The search term cannot be empty.");
			return;
		}

		var query = '/search?query=' + encodeURI(this.state.query) + '&orderBy=' + this.state.orderBy + '&maxResults=' + this.state.maxResults;
		fetch(query).then(res => {
			return res.json();
		}).then(data => {
			if (data.error == true) {
				this.showErrorMessage(data.message);
				return;
			}

			var regions = {};
			var subregions = {};
			data.forEach(function (d) {
				console.log(d);
				if (regions.hasOwnProperty(d.region)) {
					regions[d.region] = regions[d.region] + 1;
				} else {
					regions[d.region] = 1;
				}

				if (subregions.hasOwnProperty(d.subregion)) {
					subregions[d.subregion] = subregions[d.subregion] + 1;
				} else {
					subregions[d.subregion] = 1;
				}
			});
			this.setState({ results: data, regions: regions, subregions: subregions });
		}).catch(err => {
			console.log(err)
			this.showErrorMessage(err);
		});
	}

	/**
	 * Render the component
	 */
	render() {
		//	console.log(this.state);
		return (
			<div className="container">

				<div className="card"><div className="card-body">
					<form onSubmit={this.handleSubmit}>
						<div className="input-group">
							<input id="search-query" className="form-control" type="text" value={this.state.query} onChange={this.queryChange} />
							<button className="btn btn-primary" type="submit" onClick={this.handleSubmit}>Search</button>
						</div>
						<div className="input-group">
							<div className="input-group-prepend"><span className="input-group-text">Order By</span></div>
							<select onChange={this.orderByChange} className="form-control">
								<option value="name">Name</option>
								<option value="population">Population</option>
							</select>

							<div className="input-group-prepend"><span className="input-group-text">Max Results</span></div>
							<select onChange={this.maxResultsChange} defaultValue="50" className="form-control">
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="75">75</option>
								<option value="100">100</option>
							</select>
						</div>
					</form>
				</div></div>

				<ErrorMessage message={this.state.error} />

				{this.state.results.map(r => (
					<RestCountry country={r} key={r.alpha2Code} />
				))}

				<div className={Object.entries(this.state.regions).length === 0 ? "card bg-light hide" : "card bg-light"}>
					<div className="card-header">Regions</div>
					<div className="card-body regions">
						{Object.keys(this.state.regions).map(region => (
							<div key={region}>
								<span>{region}</span>
								<span>{this.state.regions[region]}</span>
							</div>
						))}
					</div></div>

				<div className={Object.entries(this.state.subregions).length === 0 ? "card bg-light hide" : "card bg-light"}>
					<div className="card-header">Sub Regions</div>
					<div className="card-body regions">
						{Object.keys(this.state.subregions).map(region => (
							<div key={region}>
								<span>{region}</span>
								<span>{this.state.subregions[region]}</span>
							</div>
						))}
					</div></div>

				<div className={this.state.results.length == 0 ? "card bg-light hide" : "card bg-light"}><div className="card-header">
					{this.state.results.length} Total Countries
				</div></div>
			</div>
		);
	}
}

// Component for rendering individual countries.
class RestCountry extends Component {
	render() {
		return (
			<div className="card bg-light">
				<div className="card-header">
					{this.props.country.name.replace("&#39;", "'") + "\t(" + this.props.country.alpha2Code + ", " + this.props.country.alpha3Code + ")"}
				</div>
				<div className="card-body">
					<div className="country-flag"><img src={this.props.country.flag} /></div>
					<div className="country-region">
						<h5>Region</h5>
						{this.props.country.region}
					</div>
					<div className="country-subregion">
						<h5>Sub Region</h5>
						{this.props.country.subregion}
					</div>
					<div className="country-population">
						<h5>Population</h5>
						{this.props.country.population}
					</div>
					<div className="country-languages">
						<h5>Languages</h5>
						{this.props.country.languages.map(l => (
							<div key={l.iso639_1.toString()}>{l.name}</div>
						))}</div>
				</div></div>
		);
	}
}

// Component for rendering error messages
class ErrorMessage extends Component {
	render() {
		return (
			<div className={this.props.message == '' ? 'card bg-danger text-white hide' : 'card bg-danger text-white'} id="error-div">
				<div className="card-body">
					<h5>{this.props.message}</h5>
				</div></div>
		);
	}
}

// Render the search component
ReactDOM.render(<RestCountrySearch />, document.getElementById('search'));

