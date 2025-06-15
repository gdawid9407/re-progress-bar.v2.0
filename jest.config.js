module.exports = {
  transform: {
    "^.+\\.js$": "babel-jest"
  },
  testEnvironment: "jsdom",
  testMatch: ["**/tests/js/**/*.test.js"]
};
