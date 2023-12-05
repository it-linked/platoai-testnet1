@extends('panel.layout.app')
@section('title', 'Search')

@section('content')

<!--iframe id="webViewer" style="height:600px" src="https://platodata.network/"></iframe-->


<div class="page-header">
	<div class="container-xl">
		<div class="row g-2 items-center justify-between max-md:flex-col max-md:items-start max-md:gap-4">
			<div class="col">
				<div class="page-pretitle">
					{{ __('User Search') }}
				</div>
				<h2 class="mb-2 page-title">
					{{ __('Welcome') }}, {{ \Illuminate\Support\Facades\Auth::user()->name }}.
				</h2>
			</div>

		</div>
	</div>
</div>
<!-- Page body -->
<div class="page-body">
	<div class="container-xl">
		<div class="row row-deck row-cards">
			<div class="flex items-center lg:-order-1 max-lg:w-full max-lg:fixed max-lg:bottom-16 max-lg:left-0 max-lg:z-50">
				<form class="navbar-search group !me-2 max-lg:hidden max-lg:[&.show]:flex max-lg:[&.collapsing]:flex max-lg:m-0 max-lg:w-full max-lg:!me-0" id="brave-search" autocomplete="off" novalidate>
					<div class="w-full input-icon max-lg:p-3 max-lg:bg-[#fff] max-lg:dark:bg-zinc-800">
						<span class="input-icon-addon">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none" />
								<path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
								<path d="M21 21l-6 -6" />
							</svg>
						</span>
						<input type="search" class="form-control peer max-lg:!rounded-md dark:!bg-zinc-900" id="top_ai_search_word" onkeydown="return event.key != 'Enter';" placeholder="{{__('Search .....')}}" aria-label="">
						<span class="absolute top-1/2 -translate-y-1/2 !end-[20px]">
							<span class="spinner-border spinner-border-sm text-muted hidden group-[.is-searching]:block" role="status"></span>
						</span>


					</div>
				</form>
			</div>
		</div>
		<div class="navbar-search-results  top-[calc(100%+17px)] !start-0 bg-[#fff] shadow-[0_10px_70px_rgba(0,0,0,0.1)] rounded-md w-[100%] max-h-[380px] overflow-y-auto group-[.done-searching]:block dark:!bg-[--tblr-bg-surface] max-lg:top-auto max-lg:bottom-full max-lg:start-0 max-lg:end-0 max-lg:w-auto" id="brave_search_results" style="z-index: 999;">
			<!-- Search results here -->
			<h3 class="m-0 py-[0.75rem] px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] text-[1rem] font-medium">{{__('Search results')}}</h3>
			<div class="ai-search-results-container">



			</div>
		</div>
	</div>
</div>

@if($setting->hosting_type != 'high')
<input type="hidden" id="guest_id" value="{{$apiUrl}}">
<input type="hidden" id="guest_event_id" value="{{$apikeyPart1}}">
<input type="hidden" id="guest_look_id" value="{{$apikeyPart2}}">
<input type="hidden" id="guest_product_id" value="{{$apikeyPart3}}">
@endif

@endsection

@section("script")

<script>
	const guest_event_id = document.getElementById("guest_event_id").value;
	const guest_look_id = document.getElementById("guest_look_id").value;
	const guest_product_id = document.getElementById("guest_product_id").value;
	const guest_id = document.getElementById("guest_id").value;
	const resultsContainer = document.querySelector('.ai-search-results-container');

	const messages = [];
	messages.push({
		role: "system",
		content: "You are a helpful assistant.",
	});

	const generate = async (prompt) => {
		return new Promise(async (resolve, reject) => {
			const div = $("<div></div>");
			$(resultsContainer).append(div);

			"use strict";

			const typingEl = document.querySelector('.tox-edit-area > .lqd-typing');
			const chunk = [];
			let streaming = true;
			let result = "";

			const nIntervId = setInterval(function() {
				if (chunk.length == 0 && !streaming) {
					clearInterval(nIntervId);
					const appLoadingIndicator = document.querySelector(
						"#app-loading-indicator"
					);
					if (appLoadingIndicator) {
						appLoadingIndicator.classList.add("opacity-0");
					}
					const workbookRegenerate = document.querySelector(
						"#workbook_regenerate"
					);
					if (workbookRegenerate) {
						workbookRegenerate.classList.remove("hidden");
					}
					//saveResponse(prompt, result, message_no, book_id);
					messages.push({
						role: "system",
						content: result,
					});
				}

				const text = chunk.shift();
				if (text) {
					result += text;
					const lastDiv = resultsContainer.lastElementChild;
					$(lastDiv).html(result);
				}
			}, 20);

			const prompt1 = atob(guest_event_id);
			const prompt2 = atob(guest_look_id);
			const prompt3 = atob(guest_product_id);

			const bearer = prompt1 + prompt2 + prompt3;

			let guest_id2 = atob(guest_id);


			messages.push({
				role: "user",
				content: prompt,
			});

			const model = "gpt-3.5-turbo-16k"; //getRandomModel(models);
			try {
				const response = await fetch(guest_id2, {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${bearer}`,
					},
					body: JSON.stringify({
						model: model,
						messages: messages,
						stream: true,
					}),
				});

				if (response.status != 200) {
					throw response;
				}

				const reader = response.body.getReader();
				const decoder = new TextDecoder("utf-8");

				while (true) {
					const {
						done,
						value
					} = await reader.read();
					if (done) {
						streaming = false;
						break;
					}

					const chunk1 = decoder.decode(value);
					const lines = chunk1.split("\n");

					const parsedLines = lines
						.map((line) => line.replace(/^data: /, "").trim())
						.filter((line) => line !== "" && line !== "[DONE]")
						.map((line) => {
							try {
								return JSON.parse(line);
							} catch (ex) {}
							return null;
						});

					for (const parsedLine of parsedLines) {
						if (!parsedLine) continue;
						const {
							choices
						} = parsedLine;
						const {
							delta
						} = choices[0];
						const {
							content
						} = delta;

						if (content) {
							chunk.push(content.replace(/(?:\r\n|\r|\n)/g, "<br> "));
						}
					}
				}
			} catch (error) {
				switch (error.status) {
					case 429:
						toastr.error(
							"Api Connection Error. You hit the rate limits of openai requests. Please check your Openai API Key."
						);
						break;
					default:
						toastr.error(
							"Api Connection Error. Please contact the system administrator via Support Ticket. Error is: API Connection failed due to API keys."
						);
				}
				// submitBtn.classList.remove("lqd-form-submitting");
				const appLoadingIndicator = document.querySelector(
					"#app-loading-indicator"
				);
				if (appLoadingIndicator) {
					appLoadingIndicator.classList.add("opacity-0");
				}
				const workbookRegenerate = document.querySelector("#workbook_regenerate");
				if (workbookRegenerate) {
					workbookRegenerate.classList.remove("hidden");
				}
				// submitBtn.disabled = false;
				if (typingEl) {
					typingEl.classList.add("lqd-is-hidden");
				}
				streaming = false;
				reject(error);
			}
		});
	};

	// Mock API call function (replace with actual implementation)
	const generateApiResponse = async (prompt) => {

		try {
			const apiResponse = await generate(prompt);
			return apiResponse;
		} catch (error) {
			if (error.status === 429) {
				toastr.error("Rate limit reached. Waiting for 10 seconds.");
				await sleep(3000); // Sleep for 60 seconds
				return generateApiResponse(prompt); // Retry the request
			} else {
				await sleep(3000); // Sleep for 60 seconds
				return generateApiResponse(prompt); // Retry the request
			}
		}

	};


	let timeout;

	// Function to handle the input event
	const handleInput = (event) => {
		// Clear the previous timeout
		clearTimeout(timeout);

		// Get the input value
		const inputValue = event.target.value;

		// Set a new timeout
		timeout = setTimeout(() => {
			// Call the API function with the input value
			generateApiResponse(inputValue);
		}, 1000); // Set the delay to 1000 milliseconds (1 second)
	};

	// Add the handleInput function to the input field
	const inputField = document.getElementById('top_ai_search_word');
	inputField.addEventListener('input', handleInput);
</script>



@endsection