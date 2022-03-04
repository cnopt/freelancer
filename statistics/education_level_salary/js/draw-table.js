async function drawChart() {

  // Access dataset stored in JSON file

  const dataset = await d3.json("./data/edu_level_salary_bracket.json")

  // create all the accessors to ensure datatypes are correct
  const sexAccessor = d => d.sex
  const sexes = ["female", "male"]
  const sexIds = d3.range(sexes.length)

  const educationAccessor = d => d.edu
  const educationNames = ["Diploma", "Degree", "Masters", "PhD"]
  const educationIds = d3.range(educationNames.length)

  const salaryAccessor = d => d.salary
  const salaryNames = ["0-10k", "10-20k", "20-30k", "30-40k", "40-50k", "over 50k"]
  const salaryIds = d3.range(salaryNames.length)

  // join sex and education level into one string
  const getStatusKey = ({sex, edu_lvl}) => [sex, edu_lvl].join("--")

  // generate stacked probabilities as to where each education level will end up
  const stackedProbabilities = {}
  dataset.forEach(startingPoint => {
    const key = getStatusKey(startingPoint)
    let stackedProbability = 0
    stackedProbabilities[key] = salaryNames.map((salary, i) => {
      stackedProbability += (startingPoint[salary] / 100)
      if (i == salaryNames.length - 1) {
        // account for rounding error
        return 1
      } else {
        return stackedProbability
      }
    })
  })

  console.log(stackedProbabilities) 

  let currentPersonId = 0
  // generate node representing a person
  function generatePerson(elapsed) {
    currentPersonId++
    // assign person's set, education level, stacked proability, and salary level
    const sex = getRandomValue(sexIds)
    const edu = getRandomValue(educationIds)
    const statusKey = getStatusKey({
      sex: sexes[sex],
      edu_lvl: educationNames[edu],
    })
    const probabilities = stackedProbabilities[statusKey]
    const salary = d3.bisect(probabilities, Math.random())

    return {
      id: currentPersonId,
      sex,
      edu,
      salary,
      startTime: elapsed + getRandomNumberInRange(-0.1, 0.1),
      yJitter: getRandomNumberInRange(-15, 15),
    }
  }

  console.log(generatePerson())

  // Create chart dimensions
  // account for bounding box

  const width = d3.min([
    window.innerWidth * 0.9,
    1200
  ])
  let dimensions = {
    width: width,
    height: 500,
    margin: {
      top: 10,
      right: 200,
      bottom: 10,
      left: 120,
    },
    pathHeight: 50,
    endsBarWidth: 15,
    endingBarPadding: 3,
  }
  dimensions.boundedWidth = dimensions.width
    - dimensions.margin.left
    - dimensions.margin.right
  dimensions.boundedHeight = dimensions.height
    - dimensions.margin.top
    - dimensions.margin.bottom

  // Draw SVG canvas

  const wrapper = d3.select("#wrapper")
    .append("svg")
      .attr("width", dimensions.width)
      .attr("height", dimensions.height)

  const bounds = wrapper.append("g")
      .style("transform", `translate(${
        dimensions.margin.left
      }px, ${
        dimensions.margin.top
      }px)`)

  // create x/y scales

  const xScale = d3.scaleLinear()
    .domain([0, 1])
    .range([0, dimensions.boundedWidth])
    .clamp(true)

  const startYScale = d3.scaleLinear()
    .domain([educationIds.length, -1])
    .range([0, dimensions.boundedHeight])

  const endYScale = d3.scaleLinear()
    .domain([salaryIds.length, -1])
    .range([0, dimensions.boundedHeight])

  const yTransitionProgressScale = d3.scaleLinear()
    .domain([0.45, 0.55]) // x progress
    .range([0, 1])        // y progress
    .clamp(true)

  const colorScale = d3.scaleLinear()
    .domain(d3.extent(educationIds))
    .range(["#12CBC4", "#B53471"])
    .interpolate(d3.interpolateHcl)

  // draw chart+data

  const linkLineGenerator = d3.line()
    .x((d, i) => i * (dimensions.boundedWidth / 5))
    .y((d, i) => i <= 2
      ? startYScale(d[0])
      : endYScale(d[1])
    )
    .curve(d3.curveMonotoneX)
  const linkOptions = d3.merge(
    educationIds.map(startId => (
      salaryIds.map(endId => (
        new Array(6).fill([startId, endId])
      ))
    ))
  )
  const linksGroup = bounds.append("g")
  const links = linksGroup.selectAll(".category-path")
    .data(linkOptions)
    .enter().append("path")
      .attr("class", "category-path")
      .attr("d", linkLineGenerator)
      .attr("stroke-width", dimensions.pathHeight)

  // draw axis information and labelling

  const startingLabelsGroup = bounds.append("g")
      .style("transform", "translateX(-20px)")

  const startingLabels = startingLabelsGroup.selectAll(".start-label")
    .data(educationIds)
    .enter().append("text")
      .attr("class", "label start-label")
      .attr("y", (d, i) => startYScale(i))
      .text((d, i) => sentenceCase(educationNames[i]))

  const startLabel = startingLabelsGroup.append("text")
      .attr("class", "start-title")
      .attr("y", startYScale(educationIds[educationIds.length - 1]) - 65)
      .text("Education")
  const startLabelLineTwo = startingLabelsGroup.append("text")
      .attr("class", "start-title")
      .attr("y", startYScale(educationIds[educationIds.length - 1]) - 50)
      .text("Level")

  const startingBars = startingLabelsGroup.selectAll(".start-bar")
    .data(educationIds)
    .enter().append("rect")
      .attr("x", 20)
      .attr("y", d => startYScale(d) - (dimensions.pathHeight/ 2))
      .attr("width", dimensions.endsBarWidth)
      .attr("height", dimensions.pathHeight)
      .attr("fill", colorScale)

  const endingLabelsGroup = bounds.append("g")
      .style("transform", `translateX(${
        dimensions.boundedWidth + 20
      }px)`)

  const endingLabels = endingLabelsGroup.selectAll(".end-label")
    .data(salaryNames)
    .enter().append("text")
      .attr("class", "label end-label")
      .attr("y", (d, i) => endYScale(i) - 15)
      .text(d => d)

  const maleMarkers = endingLabelsGroup.selectAll(".male-marker")
    .data(salaryIds)
    .enter().append("circle")
      .attr("class", "ending-marker male-marker")
      .attr("r", 5.5)
      .attr("cx", 5)
      .attr("cy", d => endYScale(d) + 5)

  const trianglePoints = [
    "-7,  6",
    " 0, -6",
    " 7,  6",
  ].join(" ")
  const femaleMarkers = endingLabelsGroup.selectAll(".female-marker")
    .data(salaryIds)
    .enter().append("polygon")
      .attr("class", "ending-marker female-marker")
      .attr("points", trianglePoints)
      .attr("transform", d => `translate(5, ${endYScale(d) + 20})`)

  const legendGroup = bounds.append("g")
      .attr("class", "legend")
      .attr("transform", `translate(${dimensions.boundedWidth}, 5)`)

  const femaleLegend = legendGroup.append("g")
      .attr("transform", `translate(${
        - dimensions.endsBarWidth * 1.5
        + dimensions.endingBarPadding
        + 1
      }, 0)`)
  femaleLegend.append("polygon")
      .attr("points", trianglePoints)
      .attr("transform", "translate(-7, 0)")
  femaleLegend.append("text")
      .attr("class", "legend-text-left")
      .text("Female")
      .attr("x", -20)
  femaleLegend.append("line")
      .attr("class", "legend-line")
      .attr("x1", -dimensions.endsBarWidth / 2 + 1)
      .attr("x2", -dimensions.endsBarWidth / 2 + 1)
      .attr("y1", 12)
      .attr("y2", 37)

  const maleLegend = legendGroup.append("g")
      .attr("transform", `translate(${
        - dimensions.endsBarWidth / 2
        - 4
      }, 0)`)
  maleLegend.append("circle")
      .attr("r", 5.5)
      .attr("transform", "translate(5, 0)")
  maleLegend.append("text")
      .attr("class", "legend-text-right")
      .text("Male")
      .attr("x", 15)
  maleLegend.append("line")
      .attr("class", "legend-line")
      .attr("x1", dimensions.endsBarWidth / 2 - 3)
      .attr("x2", dimensions.endsBarWidth / 2 - 3)
      .attr("y1", 12)
      .attr("y2", 37)

  // interactions

  const maximumPeople = 10000
  let people = []
  const markersGroup = bounds.append("g")
      .attr("class", "markers-group")
  const endingBarGroup = bounds.append("g")
      .attr("transform", `translate(${dimensions.boundedWidth}, 0)`)

  function updateMarkers(elapsed) {
    const xProgressAccessor = d => (elapsed - d.startTime) / 5000
    if (people.length < maximumPeople) {
      people = [
        ...people,
        ...d3.range(2).map(() => generatePerson(elapsed)),
      ]
    }

    const females = markersGroup.selectAll(".marker-circle")
      .data(people.filter(d => (
        xProgressAccessor(d) < 1
        && sexAccessor(d) == 0
      )), d => d.id)
      females.enter().append("circle")
        .attr("class", "marker marker-circle")
        .attr("r", 5.5)
        .style("opacity", 0)
      females.exit().remove()

    const males = markersGroup.selectAll(".marker-triangle")
      .data(people.filter(d => (
        xProgressAccessor(d) < 1
        && sexAccessor(d) == 1
      )), d => d.id)
      males.enter().append("polygon")
        .attr("class", "marker marker-triangle")
        .attr("points", trianglePoints)
        .style("opacity", 0)
      males.exit().remove()

    const markers = d3.selectAll(".marker")

    markers.style("transform", d => {
          const x = xScale(xProgressAccessor(d))
          const yStart = startYScale(educationAccessor(d))
          const yEnd = endYScale(salaryAccessor(d))
          const yChange = yEnd - yStart
          const yProgress = yTransitionProgressScale(
            xProgressAccessor(d)
          )
          const y =  yStart
            + (yChange * yProgress)
            + d.yJitter
          return `translate(${ x }px, ${ y }px)`
        })
        .attr("fill", d => colorScale(educationAccessor(d)))
      .transition().duration(100)
        .style("opacity", d => xScale(xProgressAccessor(d)) < 10
          ? 0
          : 1
        )

    const endingGroups = salaryIds.map(endId => (
      people.filter(d => (
        xProgressAccessor(d) >= 1
        && salaryAccessor(d) == endId
      ))
    ))
    const endingPercentages = d3.merge(
      endingGroups.map((peopleWithSameEnding, endingId) => (
        d3.merge(
          sexIds.map(sexId => (
            educationIds.map(educationId => {
              const peopleInBar = peopleWithSameEnding.filter(d => (
                sexAccessor(d) == sexId
              ))
              const countInBar = peopleInBar.length
              const peopleInBarWithSameStart = peopleInBar.filter(d => (
                educationAccessor(d) == educationId
              ))
              const count = peopleInBarWithSameStart.length
              const numberOfPeopleAbove = peopleInBar.filter(d => (
                educationAccessor(d) > educationId
              )).length

              return {
                endingId,
                educationId,
                sexId,
                count,
                countInBar,
                percentAbove: numberOfPeopleAbove / (peopleInBar.length || 1),
                percent: count / (countInBar || 1),
              }
            })
          ))
        )
      ))
    )

    endingBarGroup.selectAll(".ending-bar")
      .data(endingPercentages)
      .join("rect")
        .attr("class", "ending-bar")
        .attr("x", d => -dimensions.endsBarWidth * (d.sexId + 1)
          - (d.sexId * dimensions.endingBarPadding)
        )
        .attr("width", dimensions.endsBarWidth)
        .attr("y", d => endYScale(d.endingId)
          - dimensions.pathHeight / 2
          + dimensions.pathHeight * d.percentAbove
        )
        .attr("height", d => d.countInBar
          ? dimensions.pathHeight * d.percent
          : dimensions.pathHeight
        )
        .attr("fill", d => d.countInBar
          ? colorScale(d.educationId)
          : "#dadadd"
        )

    endingLabelsGroup.selectAll(".ending-value")
      .data(endingPercentages)
      .join("text")
        .attr("class", "ending-value")
        .attr("x", d => (d.educationId) * 33
          + 47
        )
        .attr("y", d => endYScale(d.endingId)
          - dimensions.pathHeight / 2
          + 14 * d.sexId
          + 35
        )
        .attr("fill", d => d.countInBar
          ? colorScale(d.educationId)
          : "#dadadd"
        )
        .text(d => d.count)
  }
  d3.timer(updateMarkers)
}
drawChart()

// utility functions

const getRandomNumberInRange = (min, max) => Math.random() * (max - min) + min
const getRandomValue = arr => arr[Math.floor(getRandomNumberInRange(0, arr.length))]

const sentenceCase = str => [
  str.slice(0, 1).toUpperCase(),
  str.slice(1),
].join("")