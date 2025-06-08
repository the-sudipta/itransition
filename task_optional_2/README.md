# 🧮 Optional Task 2: Digit Counting

## Overview
You have been given an archive (`digits.zip`) containing a collection of image files, each depicting a single handwritten decimal digit (0–9). Your goal is to automate the process of identifying and counting how many times each digit (0 through 9) appears in the dataset.

## Solution Structure
- **`optional_task_2.ipynb`**  
  A Python script that:
  1. Unpacks `digits.zip`.
  2. Loads each image and preprocesses it for a digit-recognition model.
  3. Uses a simple convolutional neural network (CNN) trained on the MNIST dataset to predict the digit in each image.
  4. Tallies the counts of each digit.
  5. Prints the final ten-element array `[count_0, count_1, …, count_9]`.

- **`requirements.txt`**  
  Lists all Python package dependencies.

## Prerequisites
- Python 3.7 or higher
- `pip` package manager

## Installation

1. Clone or download this repository.
2. Navigate into the project directory:
   ```bash
   cd optional-task-2
   ```
3. Install dependencies:
   ```bash
   pip install -r requirements.txt
   ```

## Running the Solution

1. Place the `digits.zip` file in the project root.
2. Execute the script:
   ```bash
   python solution.py digits.zip
   ```
3. The script will output an array like:
   ```
   [523, 478, 512, 495, 501, 490, 508, 489, 494, 510]
   ```
   where each position corresponds to the count of digits 0–9, respectively.

## Sending Results

Once you have the output array and have verified the results:

- Email both the array and a link to this repository (or the code file) to **p.lebedev@itransition.com**.

## Repository Link

[View the solution code on GitHub](https://github.com/the-sudipta/itransition/blob/main/task_optional_2/optional_task_2.ipynb)

---

*Good luck, and well done on tackling the challenge!*
