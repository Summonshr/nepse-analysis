import React from "react";
import axios from "axios";
import { companies, exportUrl, importUrl } from "../../config/urls";
import _ from "lodash";
import Trash from "../../components/icons/trash";
import { confirmAlert } from "react-confirm-alert"; // Import
import "react-confirm-alert/src/react-confirm-alert.css"; // Import css
import Sortable from "sortablejs";
import { arrayMove } from "react-sortable-hoc";
import Sort from "../../components/icons/sort";

class Portfolio extends React.Component {
	constructor(props) {
		super(props);

		this.set = this.set.bind(this);
		this.assignShare = this.assignShare.bind(this);
		this.boughtPrice = this.boughtPrice.bind(this);
		this.currentPrice = this.currentPrice.bind(this);
		this.difference = this.difference.bind(this);
		this.delete = this.delete.bind(this);

		if (localStorage.getItem("portfolio-v1")) {
			this.state = JSON.parse(localStorage.getItem("portfolio-v1"));
		} else {
			this.state = this.getInitialState();
		}
		this.gaEvents("Portfolio", "Accessed", "Main Site");
	}

	getInitialState() {
		return {
			companies: [],
			company: "ADBL",
			portfolios: []
		};
	}

	gaEvents(label, action, category) {
		window.gtag("event", action, {
			event_category: category,
			event_label: label
		});
	}

	componentDidMount() {
		axios.get(companies + "?minimal=true").then(this.set);
		let el = document.getElementById("companies");
		Sortable.create(el, {
			onEnd: this.fix.bind(this),
			animation: 250,
			handle: ".sort-handler"
		});
	}

	fix({ newIndex, oldIndex }) {
		this.setState({
			portfolios: arrayMove(this.state.portfolios, oldIndex, newIndex)
		});
	}

	componentDidUpdate() {
		localStorage.setItem("portfolio-v1", JSON.stringify(this.state));
	}

	set(response) {
		this.setState({ companies: response.data.companies });
	}

	addCompany() {
		let portfolios = this.state.portfolios;

		this.gaEvents(this.state.company, "Company Added", "Main Site");

		portfolios.unshift({
			key: this.state.company + "_" + _.random(10000, 10000000),
			code: this.state.company,
			bought_at: 0,
			no_of_shares: 0
		});

		this.setState({ portfolios });
	}

	assignShare(key, insertKey, value) {
		let portfolios = this.state.portfolios.map(p => {
			if (p.key === insertKey) {
				p[key] = value;
			}
			return p;
		});

		this.setState({ portfolios });
	}

	boughtPrice(company) {
		let amount = company.bought_at * company.no_of_shares;

		if (company.bought_at === 0) {
			return 0;
		}

		let charge = 0.006;

		if (amount > 50000) {
			charge = 0.0055;
		}

		if (amount > 500000) {
			charge = 0.005;
		}

		if (amount > 2000000) {
			charge = 0.0045;
		}

		if (amount > 10000000) {
			charge = 0.004;
		}

		if (company.bought_at === 100) {
			charge = 0;
		}

		return Math.ceil(amount + amount * charge + 30);
	}
	currentPrice(company) {
		let find = _.find(this.state.companies, { code: company.code });

		if (!find) {
			return 0;
		}

		let amount = find.latest_share_price * company.no_of_shares;
		return amount;
	}

	difference(company) {
		return this.currentPrice(company) - this.boughtPrice(company);
	}

	delete(company) {
		confirmAlert({
			title: "Are you sure to do this?",
			message: "There is no undo button here.",
			buttons: [
				{
					label: "Yes",
					onClick: () => {
						let portfolios = this.state.portfolios;

						portfolios = portfolios.filter(c => c.key !== company.key);

						this.setState({ portfolios });

						this.gaEvents(this.state.company, "Company Removed", "Main Site");
					}
				},
				{
					label: "No",
					onClick: () => { }
				}
			]
		});
	}

	clear() {
		confirmAlert({
			title: "Are you sure?",
			message:
				"There is no undo button for this either. May be you want to take a back up first.",
			buttons: [
				{
					label: "Delete",
					onClick: () => {
						this.setState({ portfolios: [] });
						this.gaEvents("Cleared All", "Company Removed", "Main Site");
					}
				},
				{
					label: "Cancel",
					onClick: () => { }
				}
			]
		});
	}

	export() {
		axios
			.post(exportUrl, { json: this.state.portfolios })
			.then(response => {
				this.gaEvents("Export", "Import/Export", "Main Site");

				confirmAlert({
					title: "Your back up key is: " + response.data.key,
					message: "Please, save it.",
					buttons: [
						{
							label: "Sure",
							onClick: () => { }
						}
					]
				});
			})
			.catch(() => {
				this.gaEvents("Export Error", "Error", "Main Site");
			});
	}

	import() {
		axios
			.get(importUrl + this.state.key)
			.then(response => {
				this.gaEvents("Import", "Import/Export", "Main Site");
				this.setState({ portfolios: JSON.parse(response.data.data) });
			})
			.catch(() => {
				this.gaEvents("Import Error", "Error", "Main Site");
			});
	}

	render() {
		return (
			<div className="w-full flex flex-wrap max-w-sm mx-auto">
				<div className="w-full my-1 sm:w-5/6 sm:pr-4">
					<select
						value={this.state.company}
						onChange={event => this.setState({ company: event.target.value })}
						className="form-control"
					>
						<option value="">Select a company</option>
						{this.state.companies.map(company => (
							<option key={company.code} value={company.code}>
								{company.name}
							</option>
						))}
					</select>
				</div>
				<div className="w-full my-1 sm:w-1/6">
					<button
						disabled={!this.state.company}
						onClick={this.addCompany.bind(this)}
						className="w-full btn btn-success"
					>
						Add
          </button>
				</div>
				<div className="w-full">
					<div className="w-full" id="companies">
						{this.state.portfolios.length > 0 &&
							this.state.portfolios.map((company, index) => (
								<div
									key={company.key}
									data-id={index}
									className="w-full border p-2 bg-grey-lightest"
								>
									<h4 className="w-full flex flex-wrap justify-between mb-2 text-xl">
										<span>
											<Sort />
											{
												(
													_.find(this.state.companies, {
														code: company.code
													}) || {}
												).name
											}{" "}
											({company.code})
                    </span>
										<div className="mt-2 w-full flex flex-wrap justify-between">
											<span
												className={
													this.difference(company) > 0
														? "align-middle badge badge-success"
														: "align-middle badge badge-danger"
												}
											>
												Rs {window.currency(this.difference(company))}
											</span>
											<button
												className="text-danger ml-4 align-middle"
												onClick={() => this.delete(company)}
											>
												<Trash />
											</button>
										</div>
									</h4>
									<form class="mb-2">
										<div class="form-row align-items-center">
											<div class="col-auto w-1/3" title="No. of Shares">
												<div class="input-group" title="Bought at">
													<input
														min="0"
														step="10"
														type="number"
														className="form-control rounded-none pr-1"
														value={company.no_of_shares}
														onChange={event =>
															this.assignShare(
																"no_of_shares",
																company.key,
																event.target.value
															)
														}
														placeholder="No. of Shares"
													/>
													<div class="input-group-prepend rounded-none ">
														<div class="input-group-text rounded-none text-xs px-1">Shares</div>
													</div>
												</div>
											</div>
											<div class="col-auto w-1/3">
												<div class="input-group" title="Bought at">
													<div class="input-group-prepend rounded-none">
														<div class="input-group-text rounded-none">@</div>
													</div>
													<input
														min="0"
														step="10"
														type="number"
														className="form-control rounded-none"
														value={company.bought_at}
														onChange={event =>
															this.assignShare(
																"bought_at",
																company.key,
																event.target.value
															)
														}
														placeholder="Buying price"
													/>
												</div>
											</div>
											<div className="col-auto w-1/3">
												<p>
													= Rs <span className="text-grey-darkest font-semibold">
														{window.currency(this.boughtPrice(company))}
													</span>
												</p>
											</div>
										</div>
									</form>
									<div className="w-full flex flex-wrap mb-2 justify-between">
										<div><span className="text-grey-darkest">Current Standing: </span>
											<span className="text-grey-darkest font-semibold">
												Rs {window.currency(this.currentPrice(company))} @
												Rs{" "}
												{window.currency(
													(
														_.find(this.state.companies, {
															code: company.code
														}) || {}
													).latest_share_price
												)} per share
											</span>
										</div>
									</div>
								</div>
							))}
					</div>
					{this.state.portfolios.length > 0 && (
						<div className="w-full flex flex-wrap mb-2 justify-between border p-2 bg-red-lightest">
							<div className="w-full">
								<span className="text-grey-darkest">Total Stocks: {" "}
									<span className="font-bold">
										{this.state.portfolios.length}
									</span>
								</span>
							</div>
							<div className="w-full">
								<span className="text-grey-darkest">
									No. of Shares: {" "}
									<span className="font-semibold">
										{_.sum(
											_
												.map(this.state.portfolios, "no_of_shares")
												.map(num => parseInt(num, 10))
										)}
									</span>
								</span>
							</div>
							<div className="w-full">
								<span className="text-grey-darkest">
									Average buying price: Rs{" "}
								</span>
								<span className="font-semibold">
									{window.currency(
										Math.ceil(
											_.mean(
												_
													.map(this.state.portfolios, "bought_at")
													.map(num => parseInt(num, 20))
													.filter(Boolean)
											)
										)
									)}
								</span>
							</div>
							<div className="w-full">
								<span className="text-grey-darkest">
									Investment: Rs{" "}
								</span>
								<span className="font-semibold">
									{window.currency(
										_.sum(this.state.portfolios.map(this.boughtPrice))
									)}
								</span>
							</div>
							<div className="w-full">
								<span className="text-grey-darkest">
									Total current value: Rs{" "}
									<span className="font-semibold">
										{window.currency(
											_.sum(this.state.portfolios.map(this.currentPrice))
										)}
									</span>
								</span>
							</div>
							<div className="w-full flex flex-wrap justify-between">
								<span className="text-grey-darkest">
									Current status: Rs{" "}
									<span className="font-semibold">
										{window.currency(
											_.sum(
												this.state.portfolios.map(this.difference).filter(Boolean)
											)
										)}
									</span>
								</span>
								{_.sum(this.state.portfolios.map(this.difference)) > 0 ? (
									<span className="badge badge-success leading-loose">
										In profit
                  					</span>
								) : (
										<span className="badge badge-danger leading-loose">
											In loss
									</span>
									)}
							</div>
							<div className="w-full flex flex-wrap justify-between mt-2">
								<button
									className="btn btn-sm btn-danger"
									onClick={this.clear.bind(this)}
								>
									Clear all data
                </button>
								<button
									className="btn btn-sm bg-teal-darkest text-white"
									onClick={this.export.bind(this)}
								>
									Back Up
                </button>
							</div>
						</div>
					)}
					{this.state.portfolios.length === 0 && (
						<div className="alert alert-info">
							You do not have any portfolios yet. Please read below notices
							before you start.
              {!this.state.upload && (
								<button
									onClick={event => this.setState({ upload: true })}
									className="btn btn-primary btn-sm ml-2"
								>
									Import
                </button>
							)}
							{this.state.upload && (
								<div className="w-full flex flex-wrap">
									<div class="w-4/5">
										<input
											type="text"
											placeholder="Enter the back up key"
											onChange={event =>
												this.setState({ key: event.target.value })
											}
											className="form-control"
										/>
									</div>
									<div className="w-1/5 pl-1">
										<button
											onClick={this.import.bind(this)}
											class="btn btn-primary btn-block"
										>
											Import
                    </button>
									</div>
								</div>
							)}
						</div>
					)}
				</div>
				<div className="w-full py-4 text-grey-darkest">
					<h4 className="font-semibold">Your concerns</h4>
					<ul className="pb-4 pt-1">
						<li>All the data are stored in your device only.</li>
						<li>
							The total investment includes the appropriate broker commission
							too.
           				 </li>
						<li>Values are refreshed only once a 10 minute.</li>
						<li>In case of adding bonus share, enter buying price as 0</li>
						<li>In case of buying right share, enter buying price at 100</li>
						<li>
							Exact version, we have a android app{" "}
							<a href="https://exp-shell-app-assets.s3.us-west-1.amazonaws.com/android/%40summonshr/stockApp-9b557882c1697212347b61881f8f347b-signed.apk" download="stocknp.apk">
								here
							</a>. (Size: 26MB)
						</li>
						<li>
							Any advice you have, please send it @{" "}
							<a href="mailto:summonshr@gmail.com">summonshr@gmail.com</a>. I
							will review it.
            			</li>
						<li>
							Please support me by donating some amount through esewa and khalti
              in ID <a href="tel:9841145614">9841145614</a> (Suman Shrestha).
            </li>
					</ul>
					<h4>My plans</h4>
					<ul className="pb-4 pt-1">
						<li>Currently Empty</li>
						<li>Have some advice, Please send it at <a href="mailto:summonshr@gmail.com">summonshr@gmail.com</a></li>
					</ul>
				</div>
			</div>
		);
	}
}

export default props => <Portfolio {...props.match} />;
